<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SyaratSurat\BulkDeleteSyaratSuratAction;
use App\Attributes\AdminModul;
use App\DataTables\Admin\SyaratSuratDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SyaratSuratRequest;
use App\Models\SyaratSurat;
use App\Repositories\SyaratSuratRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

#[AdminModul(modul: 'layanan-surat', subModul: 'daftar-persyaratan')]
class SyaratSuratController extends Controller
{
    public function index()
    {
        return view('admin.syaratan_surat.index');
    }

    public function datatables(SyaratSuratDataTable $dataTable): JsonResponse
    {
        return $dataTable->ajax();
    }

    public function form(?SyaratSurat $syaratSurat = null)
    {
        $action = $syaratSurat ? 'Ubah' : 'Tambah';
        $formAction = $syaratSurat
            ? route('surat_mohon.update', $syaratSurat->ref_syarat_id)
            : route('surat_mohon.insert');

        return view('admin.syaratan_surat.form', [
            'action'           => $action,
            'form_action'      => $formAction,
            'ref_syarat_surat' => $syaratSurat,
        ]);
    }

    public function store(SyaratSuratRequest $request): RedirectResponse
    {
        SyaratSurat::create($request->validated());

        return redirect()->route('surat_mohon.index')
            ->with('success', __('notification.created.success'));
    }

    public function update(SyaratSuratRequest $request, SyaratSurat $syaratSurat): RedirectResponse
    {
        $syaratSurat->update($request->validated());

        return redirect()->route('surat_mohon.index')
            ->with('success', __('notification.updated.success'));
    }

    public function destroy(SyaratSurat $syaratSurat, SyaratSuratRepository $repository): RedirectResponse
    {
        $repository->deleteWithFormatSuratExist($syaratSurat->ref_syarat_id);

        return redirect()->route('surat_mohon.index')
            ->with('success', __('notification.deleted.success'));
    }

    public function destroyAll(Request $request, BulkDeleteSyaratSuratAction $action): RedirectResponse
    {
        $success = $action->execute($request->input('id_cb', []));

        return redirect()->route('surat_mohon.index')
            ->with(
                $success ? 'success' : 'error',
                $success ? __('notification.deleted.success') : __('notification.deleted.error'),
            );
    }
}
