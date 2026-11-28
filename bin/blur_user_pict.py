#!/usr/bin/env python3
"""
Blur faces and name-badge text in village resident photos in-place.

Processes every image in --dir, applying a strong Gaussian blur to:
  • All detected faces   (via face_recognition / dlib HOG — no GPU required)
  • All detected text    (via easyocr if installed; silently skipped if absent)

Files are overwritten in-place. Run this BEFORE distributing the dataset.

Usage (called by AcakService when foto_mode = 'blur'):
    python bin/blur_user_pict.py --dir desa/desa/upload/user_pict

Requirements (mandatory):
    pip install face-recognition opencv-python pillow numpy

Requirements (optional — enables badge/name-tag blurring):
    pip install easyocr          # pulls in PyTorch (~2 GB first install)
"""

import argparse
import sys
from pathlib import Path

import cv2
import numpy as np
from PIL import Image

try:
    import face_recognition
    FACE_REC_OK = True
except ImportError:
    FACE_REC_OK = False

try:
    import easyocr
    _ocr_reader = easyocr.Reader(['id', 'en'], gpu=False, verbose=False)
    OCR_OK = True
except ImportError:
    OCR_OK = False
except Exception:
    OCR_OK = False

IMAGE_EXTENSIONS = {'.jpg', '.jpeg', '.png', '.webp'}


def _odd(n: int) -> int:
    """Return n if odd, else n+1. Gaussian kernel size must be odd and ≥ 1."""
    n = max(n, 1)
    return n if n % 2 == 1 else n + 1


def _blur_region(img: np.ndarray, top: int, left: int, bottom: int, right: int) -> np.ndarray:
    """Apply a strong Gaussian blur to a rectangular region of img (BGR)."""
    h, w = img.shape[:2]
    top    = max(0, top)
    left   = max(0, left)
    bottom = min(h, bottom)
    right  = min(w, right)

    if bottom <= top or right <= left:
        return img

    region_h = bottom - top
    region_w = right  - left

    # Kernel proportional to region size — large enough to fully obscure detail.
    k = _odd(max(region_h, region_w) // 3)
    img[top:bottom, left:right] = cv2.GaussianBlur(
        img[top:bottom, left:right], (k, k), 0
    )
    return img


def _expand(top: int, right: int, bottom: int, left: int,
            ratio: float, img_h: int, img_w: int) -> tuple:
    fh, fw = bottom - top, right - left
    pv, ph = int(fh * ratio), int(fw * ratio)
    return (max(0, top - pv), min(img_w, right + ph),
            min(img_h, bottom + pv), max(0, left - ph))


def process_image(path: Path) -> str:
    """
    Blur faces and text in the image at `path`, overwriting the file.
    Returns a human-readable status string.
    """
    img_rgb = face_recognition.load_image_file(str(path)) if FACE_REC_OK else None

    # Convert to BGR for OpenCV processing
    if img_rgb is not None:
        img_bgr = cv2.cvtColor(img_rgb, cv2.COLOR_RGB2BGR)
    else:
        img_bgr = cv2.imread(str(path))
        if img_bgr is None:
            return 'unreadable — skipped'

    h, w = img_bgr.shape[:2]
    faces_blurred = 0
    text_blurred  = 0

    # ── Face detection ─────────────────────────────────────────────────────
    if FACE_REC_OK and img_rgb is not None:
        locs = face_recognition.face_locations(img_rgb, model='hog')
        for (top, right, bottom, left) in locs:
            # Expand slightly to cover forehead/chin fully
            top2, right2, bottom2, left2 = _expand(top, right, bottom, left, 0.15, h, w)
            img_bgr = _blur_region(img_bgr, top2, left2, bottom2, right2)
            faces_blurred += 1

    # ── Text / badge detection ─────────────────────────────────────────────
    if OCR_OK:
        results = _ocr_reader.detect(
            cv2.cvtColor(img_bgr, cv2.COLOR_BGR2RGB),
            slope_ths=0.3,
            min_size=10,
        )
        # easyocr.detect returns (horizontal_list, free_list)
        horizontal = results[0] if results else []
        for bbox in horizontal:
            # bbox = [x_min, x_max, y_min, y_max]
            if len(bbox) < 4:
                continue
            x_min, x_max, y_min, y_max = (int(v) for v in bbox[:4])
            img_bgr = _blur_region(img_bgr, y_min, x_min, y_max, x_max)
            text_blurred += 1

    # ── Write full-size image back ─────────────────────────────────────────
    suffix = path.suffix.lower()
    if suffix in ('.jpg', '.jpeg'):
        cv2.imwrite(str(path), img_bgr, [cv2.IMWRITE_JPEG_QUALITY, 92])
    elif suffix == '.png':
        cv2.imwrite(str(path), img_bgr)
    else:
        result_rgb = cv2.cvtColor(img_bgr, cv2.COLOR_BGR2RGB)
        Image.fromarray(result_rgb).save(str(path), quality=92)

    # ── Regenerate thumbnail from the blurred image ────────────────────────
    # Thumbnails (kecil_*) are derived from the full-size blurred image rather
    # than being blurred independently, so the result is guaranteed to match.
    thumb_path = path.parent / f'kecil_{path.name}'
    if thumb_path.exists():
        thumb = cv2.resize(img_bgr, (150, 150), interpolation=cv2.INTER_AREA)
        t_suffix = thumb_path.suffix.lower()
        if t_suffix in ('.jpg', '.jpeg'):
            cv2.imwrite(str(thumb_path), thumb, [cv2.IMWRITE_JPEG_QUALITY, 88])
        else:
            cv2.imwrite(str(thumb_path), thumb)

    parts = []
    if faces_blurred:
        parts.append(f'{faces_blurred} wajah')
    if text_blurred:
        parts.append(f'{text_blurred} teks')
    if not parts:
        parts.append('tidak ada wajah/teks terdeteksi')
    return ', '.join(parts)


def main() -> None:
    parser = argparse.ArgumentParser(description='Blur faces and name badges in-place')
    parser.add_argument(
        '--dir',
        required=True,
        help='Directory containing images to process (files are overwritten)',
    )
    parser.add_argument(
        '--files',
        nargs='*',
        metavar='FILENAME',
        help=(
            'Specific base filenames to process (e.g. 3.jpg Kades-yoyon.jpg). '
            'When supplied, only these files are touched and their kecil_ thumbnails '
            'are regenerated. When omitted, every image in --dir is processed.'
        ),
    )
    args = parser.parse_args()

    src_dir = Path(args.dir)
    if not src_dir.is_dir():
        print(f'ERROR: directory not found: {src_dir}', file=sys.stderr)
        sys.exit(1)

    if not FACE_REC_OK:
        print('ERROR: face-recognition not installed. Run: pip install face-recognition',
              file=sys.stderr)
        sys.exit(2)

    if not OCR_OK:
        print('WARN: easyocr not installed — only faces will be blurred. '
              'Run: pip install easyocr  to enable badge/name-tag detection.',
              file=sys.stderr)

    if args.files:
        # DB-driven: process only the filenames referenced in tweb_penduduk / tweb_desa_pamong.
        # kecil_ thumbnails are regenerated automatically in process_image(); skip them here.
        images = sorted(
            src_dir / name
            for name in args.files
            if not name.startswith('kecil_')
            and Path(name).suffix.lower() in IMAGE_EXTENSIONS
            and (src_dir / name).exists()
        )
        missing = [name for name in args.files
                   if not name.startswith('kecil_') and not (src_dir / name).exists()]
        if missing:
            print(f'WARN: {len(missing)} file tidak ditemukan di folder: {", ".join(missing[:5])}'
                  + (' …' if len(missing) > 5 else ''), file=sys.stderr)
    else:
        # Standalone / fallback: process every full-size image in the folder.
        # kecil_ thumbnails are regenerated automatically in process_image().
        images = sorted(
            p for p in src_dir.iterdir()
            if p.suffix.lower() in IMAGE_EXTENSIONS and not p.name.startswith('kecil_')
        )
    total  = len(images)
    print(f'{total} gambar ditemukan di {src_dir}', flush=True)

    done = errors = 0
    for i, path in enumerate(images, 1):
        print(f'[{i:>4}/{total}] {path.name} …', end=' ', flush=True)
        try:
            status = process_image(path)
            print(status)
            done += 1
        except Exception as exc:
            print(f'ERROR: {exc}')
            errors += 1

    print(f'\nSelesai. {done} diproses, {errors} error.')


if __name__ == '__main__':
    main()
