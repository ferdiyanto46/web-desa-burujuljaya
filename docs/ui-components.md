# UI Component Catalog

- **DataTable.vue** (Lokasi: components/tables/)
  - Purpose: Tabel generik tanpa hardcode domain data.
  - Props: items. Menerima slot untuk Header dan baris data.
- **FormInput.vue / FormTextarea.vue** (Lokasi: components/forms/)
  - Purpose: Input seragam dilengkapi label dan Error validation.
  - Props: id, label, modelValue, error.
- **ImageUploader.vue** (Lokasi: components/ui/upload/)
  - Purpose: Unggah file dengan pratinjau klien.
  - Props: modelValue, error, currentImageUrl.
- **FormSelect.vue / FormDatePicker.vue**
  - Purpose: Komponen drop down dan input tanggal.
