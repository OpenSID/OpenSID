#!/bin/bash

# Script untuk update semua tema dari upstream repository
# Penggunaan: bash update-themes.sh

# Color codes untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Array tema yang akan di-update
THEMES=("seruit-lite" "wira" "lestari")

# Counter untuk tracking
UPDATED=0
FAILED=0

echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}  TEMA UPDATE SCRIPT${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""

# Check if in correct directory
if [ ! -d "storage/app/themes" ]; then
    echo -e "${RED}Error: Tidak ditemukan folder storage/app/themes${NC}"
    echo -e "${RED}Pastikan menjalankan script dari root directory project${NC}"
    exit 1
fi

# Check if git is available
if ! command -v git &> /dev/null; then
    echo -e "${RED}Error: Git tidak ter-install${NC}"
    exit 1
fi

# Update setiap tema
for theme in "${THEMES[@]}"; do
    THEME_PATH="storage/app/themes/$theme"
    
    echo -e "${YELLOW}Updating $theme...${NC}"
    
    # Check if tema folder exists
    if [ ! -d "$THEME_PATH" ]; then
        echo -e "${RED}✗ Folder $theme tidak ditemukan${NC}"
        ((FAILED++))
        continue
    fi
    
    # Navigate to tema folder
    cd "$THEME_PATH"
    
    # Initialize git if not already initialized
    if [ ! -d ".git" ]; then
        echo -e "${YELLOW}  - Initializing git repository...${NC}"
        git init > /dev/null 2>&1
        git remote add origin "https://github.com/OpenSID/tema-$theme" > /dev/null 2>&1
    fi
    
    # Check if remote exists
    if ! git remote get-url origin > /dev/null 2>&1; then
        git remote add origin "https://github.com/OpenSID/tema-$theme" > /dev/null 2>&1
    fi
    
    # Fetch latest updates
    echo -e "${YELLOW}  - Fetching updates dari https://github.com/OpenSID/tema-$theme${NC}"
    if git fetch origin main > /dev/null 2>&1; then
        # Merge with allow-unrelated-histories flag
        echo -e "${YELLOW}  - Merging changes...${NC}"
        if git merge origin/main --allow-unrelated-histories -m "Update $theme from upstream" > /dev/null 2>&1; then
            echo -e "${GREEN}✓ $theme berhasil di-update${NC}"
            ((UPDATED++))
        else
            # Handle merge conflicts
            if git status | grep -q "conflict"; then
                echo -e "${YELLOW}⚠ $theme memiliki conflict - perlu manual resolve${NC}"
                echo -e "${YELLOW}  - Jalankan: cd $THEME_PATH && git status${NC}"
                ((FAILED++))
            else
                echo -e "${GREEN}✓ $theme berhasil di-update${NC}"
                ((UPDATED++))
            fi
        fi
    else
        echo -e "${RED}✗ Gagal fetch dari upstream $theme${NC}"
        ((FAILED++))
    fi
    
    # Go back to root
    cd ../../..
    echo ""
done

echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}SUMMARY${NC}"
echo -e "${YELLOW}========================================${NC}"
echo -e "Tema berhasil di-update: ${GREEN}$UPDATED${NC}"
echo -e "Tema gagal/conflict: ${RED}$FAILED${NC}"
echo ""

# Commit all changes if there are updates
if [ $UPDATED -gt 0 ]; then
    echo -e "${YELLOW}Committing changes...${NC}"
    
    # Stage all changes
    git add storage/app/themes/
    
    # Check if there are changes to commit
    if git diff --cached --quiet; then
        echo -e "${YELLOW}Tidak ada perubahan untuk di-commit${NC}"
    else
        # Create commit message
        if [ $FAILED -eq 0 ]; then
            COMMIT_MSG="Update all themes to latest version"
        else
            COMMIT_MSG="Update themes (some with conflicts) to latest version"
        fi
        
        # Commit and push
        if git commit -m "$COMMIT_MSG" > /dev/null 2>&1; then
            echo -e "${GREEN}✓ Commit berhasil: $COMMIT_MSG${NC}"
            
            # Ask to push or not
            echo ""
            echo -e "${YELLOW}Apakah ingin push ke remote? (y/n)${NC}"
            read -r push_choice
            
            if [ "$push_choice" = "y" ] || [ "$push_choice" = "Y" ]; then
                echo -e "${YELLOW}Pushing ke remote...${NC}"
                if git push origin tema > /dev/null 2>&1; then
                    echo -e "${GREEN}✓ Push berhasil${NC}"
                else
                    echo -e "${YELLOW}⚠ Push gagal - cek koneksi atau upstream${NC}"
                fi
            else
                echo -e "${YELLOW}Skipping push - commit sudah di-local${NC}"
            fi
        else
            echo -e "${YELLOW}⚠ Commit gagal - mungkin tidak ada perubahan${NC}"
        fi
    fi
else
    echo -e "${YELLOW}Tidak ada tema yang berhasil di-update${NC}"
fi

echo ""
echo -e "${GREEN}Update script selesai!${NC}"

# Exit with status
if [ $FAILED -gt 0 ]; then
    exit 1
else
    exit 0
fi
