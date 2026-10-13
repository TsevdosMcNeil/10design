#!/bin/bash

# Prompt for URLs
read -p "Enter the OLD URL (e.g., https://10bluehost.codigo.co.uk): " OLD_URL
read -p "Enter the NEW URL (e.g., https://10digitalocean.codigo.co.uk): " NEW_URL

# Ask if dry-run mode
read -p "Do you want to run in dry-run mode? (y/n): " DRY_RUN

# Set content directory and log file
CONTENT_DIR="./content"
LOG_FILE="url_replacement.log"

echo ""
echo "🔍 Looking for occurrences of:"
echo "  $OLD_URL"
echo "➡️ To replace with:"
echo "  $NEW_URL"
echo ""
echo "📁 Searching in: $CONTENT_DIR"
echo "📝 Logging to: $LOG_FILE"
echo "" > "$LOG_FILE" # Clear previous log

# Process files
find "$CONTENT_DIR" -type f \( -name "*.md" -o -name "*.yaml" \) | while read -r file; do
    if grep -q "$OLD_URL" "$file"; then
        echo "Would update: $file" | tee -a "$LOG_FILE"
        if [[ "$DRY_RUN" != "y" && "$DRY_RUN" != "Y" ]]; then
            sed -i "s|$OLD_URL|$NEW_URL|g" "$file"
        fi
    fi
done

if [[ "$DRY_RUN" == "y" || "$DRY_RUN" == "Y" ]]; then
    echo "🧪 Dry-run complete. No files were changed."
else
    echo "✅ Replacement complete. Files updated are listed in $LOG_FILE"
fi
