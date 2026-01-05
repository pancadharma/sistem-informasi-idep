# Add Headers, Footers & Styling to DOCX Export

## 📄 Overview

PHPWord allows you to add:
- ✅ Headers (repeated on every page)
- ✅ Footers (repeated on every page)
- ✅ Page numbers
- ✅ Images in headers
- ✅ Different styling per section
- ✅ Watermarks

---

## 🎯 Quick Example: Header & Footer on Every Page

Here's how to modify your `exportDocx()` method to add headers and footers:

```php
public function exportDocx($id)
{
    $tmpDoc = null;
    try {
        $kegiatan = BTOR::getData($id);
        $this->ensureRelationshipsLoaded($kegiatan);

        $phpWord = new PhpWord();
        
        // ✅ ADD SECTION WITH HEADER & FOOTER
        $section = $phpWord->addSection();
        
        // ADD HEADER (appears on every page)
        $header = $section->addHeader();
        $headerTable = $header->addTable(['width' => 9000, 'unit' => 'pct']);
        $headerRow = $headerTable->addRow();
        $headerRow->addCell(2000)->addText('YAYASAN IDEP', ['bold' => true, 'size' => 12]);
        $headerRow->addCell(5000)->addText('BACK TO OFFICE REPORT', ['bold' => true, 'size' => 12, 'alignment' => new Jc(Jc::CENTER)]);
        $headerRow->addCell(2000)->addText(date('Y'), ['size' => 10, 'alignment' => new Jc(Jc::RIGHT)]);
        
        // ADD FOOTER (appears on every page)
        $footer = $section->addFooter();
        $footerTable = $footer->addTable(['width' => 9000, 'unit' => 'pct']);
        $footerRow = $footerTable->addRow();
        $footerRow->addCell(6000)->addText('Demosite Br. Medahan, Sukawati, Gianyar', ['size' => 9, 'italic' => true]);
        $footerRow->addCell(3000)->addText('Page {PAGE}', ['size' => 9, 'alignment' => new Jc(Jc::RIGHT)]);

        // NOW ADD YOUR DOCUMENT CONTENT
        $this->addDocxHeader($section, $kegiatan);
        $this->addDocxContent($section, $kegiatan);

        // Rest of export code...
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $tmpDoc = tempnam(sys_get_temp_dir(), 'btor_' . time() . '_');
        $phpWord->save($tmpDoc, 'Word2007');

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('YmdHis') . '.docx';
        return response()->download($tmpDoc, $filename)->deleteFileAfterSend(true);

    } catch (\Exception $e) {
        \Log::error('BTOR DOCX Export Error', ['error' => $e->getMessage()]);
        return back()->with('error', 'Gagal mengekspor: ' . $e->getMessage());
    }
}
```

---

## 📐 Option 1: Simple Text Header/Footer

```php
// SIMPLE HEADER
$header = $section->addHeader();
$header->addText('YAYASAN IDEP - BACK TO OFFICE REPORT', [
    'bold' => true,
    'size' => 12
]);
$header->addLine(['color' => '000000', 'width' => 24, 'height' => 1]);

// SIMPLE FOOTER
$footer = $section->addFooter();
$footer->addText('Page {PAGE} of {NUMPAGES}', [
    'size' => 10,
    'alignment' => new Jc(Jc::CENTER)
]);
```

---

## 📋 Option 2: Table-Based Header (Professional)

```php
$header = $section->addHeader();

$headerTable = $header->addTable([
    'width' => 9000,
    'unit' => 'pct',
    'borderSize' => 0,  // No borders
    'borderColor' => 'FFFFFF'  // White borders
]);

$row = $headerTable->addRow();

// Left column - Logo/Name
$row->addCell(3000)->addText(
    'YAYASAN IDEP',
    ['bold' => true, 'size' => 14, 'color' => '1f4788']
);

// Center column - Title
$row->addCell(3000)->addText(
    'BACK TO OFFICE REPORT',
    ['bold' => true, 'size' => 12, 'alignment' => new Jc(Jc::CENTER)]
);

// Right column - Date
$row->addCell(3000)->addText(
    'Year: ' . date('Y'),
    ['size' => 10, 'alignment' => new Jc(Jc::RIGHT)]
);

// Add separator line
$header->addLine([
    'color' => '000000',
    'width' => 24,
    'height' => 2
]);
```

---

## 🖼️ Option 3: Header with Logo Image

```php
$header = $section->addHeader();

$headerTable = $header->addTable(['width' => 9000, 'unit' => 'pct']);
$row = $headerTable->addRow();

// Add logo on left
$logoCell = $row->addCell(1500);
$logoCell->addImage(
    public_path('images/logo.png'),
    [
        'width' => 80,
        'height' => 80,
        'alignment' => 'left'
    ]
);

// Add organization info on right
$infoCell = $row->addCell(7500);
$infoCell->addText(
    'YAYASAN IDEP SELARAS ALAM',
    ['bold' => true, 'size' => 14]
);
$infoCell->addText(
    'Back to Office Report',
    ['size' => 11, 'italic' => true]
);
$infoCell->addText(
    'Generated: ' . date('d-m-Y H:i:s'),
    ['size' => 9, 'color' => '666666']
);

// Add separator
$header->addLine(['color' => '1f4788', 'width' => 24]);
```

---

## 🔢 Option 4: Professional Footer with Page Numbers

```php
$footer = $section->addFooter();

// Add line separator at top
$footer->addLine(['color' => '000000', 'width' => 24]);

// Add footer content in table
$footerTable = $footer->addTable(['width' => 9000, 'unit' => 'pct']);
$row = $footerTable->addRow();

// Left - Organization
$row->addCell(3000)->addText(
    'Yayasan IDEP Selaras Alam',
    ['size' => 9, 'bold' => true]
);

// Center - Document info
$row->addCell(3000)->addText(
    'Bali, Indonesia',
    ['size' => 9, 'alignment' => new Jc(Jc::CENTER)]
);

// Right - Page number
$row->addCell(3000)->addText(
    'Page {PAGE} of {NUMPAGES}',
    ['size' => 9, 'alignment' => new Jc(Jc::RIGHT)]
);
```

---

## 🎨 Option 5: Colored/Styled Header & Footer

```php
// COLORED HEADER
$header = $section->addHeader();

// Background color using table
$headerTable = $header->addTable(['width' => 9000, 'unit' => 'pct']);
$headerRow = $headerTable->addRow();

$headerCell = $headerRow->addCell(9000, [
    'bgColor' => '1f4788',  // Dark blue background
    'borderSize' => 0
]);

$headerCell->addText(
    'BACK TO OFFICE REPORT - YAYASAN IDEP SELARAS ALAM',
    [
        'bold' => true,
        'size' => 12,
        'color' => 'FFFFFF'  // White text
    ]
);

// STYLED FOOTER
$footer = $section->addFooter();
$footerTable = $footer->addTable(['width' => 9000, 'unit' => 'pct']);
$footerRow = $footerTable->addRow();

$footerCell = $footerRow->addCell(9000, [
    'bgColor' => 'E8E8E8',  // Light gray background
    'borderSize' => 0
]);

$footerCell->addText(
    'Telp/Fax: 62-361-908-2983 | 62-812-4658-5137 | Page {PAGE}',
    [
        'size' => 9,
        'alignment' => new Jc(Jc::CENTER)
    ]
);
```

---

## 📝 Complete Example: Full Export with Header/Footer

```php
public function exportDocx($id)
{
    $tmpDoc = null;
    try {
        $kegiatan = BTOR::getData($id);
        $this->ensureRelationshipsLoaded($kegiatan);

        $phpWord = new PhpWord();
        
        // Create section
        $section = $phpWord->addSection([
            'marginTop' => 1000,      // 1 inch top margin
            'marginBottom' => 1000,   // 1 inch bottom margin
            'marginLeft' => 1000,     // 1 inch left margin
            'marginRight' => 1000     // 1 inch right margin
        ]);

        // ADD PROFESSIONAL HEADER
        $header = $section->addHeader();
        $headerTable = $header->addTable([
            'width' => 9000,
            'unit' => 'pct',
            'borderSize' => 0
        ]);
        
        $headerRow = $headerTable->addRow();
        $headerRow->addCell(3000)->addText(
            'YAYASAN IDEP',
            ['bold' => true, 'size' => 14, 'color' => '1f4788']
        );
        $headerRow->addCell(3000)->addText(
            'BACK TO OFFICE REPORT',
            ['bold' => true, 'size' => 12, 'alignment' => new Jc(Jc::CENTER)]
        );
        $headerRow->addCell(3000)->addText(
            date('Y'),
            ['size' => 10, 'alignment' => new Jc(Jc::RIGHT)]
        );
        
        $header->addLine(['color' => '1f4788', 'width' => 24]);

        // ADD PROFESSIONAL FOOTER
        $footer = $section->addFooter();
        $footerTable = $footer->addTable(['width' => 9000, 'unit' => 'pct']);
        $footerRow = $footerTable->addRow();
        
        $footerRow->addCell(6000)->addText(
            'Demosite Br. Medahan, Sukawati, Gianyar 80582, Bali',
            ['size' => 9, 'italic' => true]
        );
        $footerRow->addCell(3000)->addText(
            'Page {PAGE}',
            ['size' => 9, 'alignment' => new Jc(Jc::RIGHT)]
        );

        // ADD DOCUMENT CONTENT
        $this->addDocxHeader($section, $kegiatan);
        $this->addDocxContent($section, $kegiatan);

        // Save and download
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $tmpDoc = tempnam(sys_get_temp_dir(), 'btor_' . time() . '_');
        $phpWord->save($tmpDoc, 'Word2007');

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('YmdHis') . '.docx';
        return response()->download($tmpDoc, $filename)
            ->deleteFileAfterSend(true);

    } catch (\Exception $e) {
        \Log::error('BTOR DOCX Export Error', ['error' => $e->getMessage()]);
        if ($tmpDoc && file_exists($tmpDoc)) {
            @unlink($tmpDoc);
        }
        return back()->with('error', 'Gagal mengekspor: ' . $e->getMessage());
    }
}
```

---

## 🎨 Available Colors

```php
// Use hex colors (without #):
'color' => '1f4788'     // Dark blue
'color' => 'FFFFFF'     // White
'color' => '000000'     // Black
'color' => 'FF0000'     // Red
'color' => '00AA00'     // Green
'color' => 'E8E8E8'     // Light gray
'color' => 'D3D3D3'     // Medium gray
```

---

## 📌 Key Spacing Options for Margins

```php
$section = $phpWord->addSection([
    'marginTop' => 1000,      // 1 inch (2.54 cm)
    'marginBottom' => 1000,
    'marginLeft' => 1440,     // 1.5 inches
    'marginRight' => 1440,
    'headerHeight' => 720,    // Space for header
    'footerHeight' => 720,    // Space for footer
]);

// Common unit conversions:
// 1 inch = 1440 twips (twentieth of a point)
// 1 cm ≈ 567 twips
// 720 twips = 0.5 inches
```

---

## 🔄 Different Headers for First Page vs Others

```php
// Only add header/footer to first section
$firstSection = $phpWord->addSection([
    'marginTop' => 1000
]);

$header = $firstSection->addHeader();
$header->addText('HEADER ON FIRST PAGE ONLY', ['bold' => true]);

// This section won't have the same header
$secondSection = $phpWord->addSection();
// Add different content without header
```

---

## 📊 Bulk Export with Headers/Footers

For your `exportBulkDocx()`, you want headers/footers on ALL pages:

```php
public function exportBulkDocx(Request $request)
{
    // ... validation code ...

    try {
        $phpWord = new PhpWord();
        $sections = [];

        foreach ($ids as $id) {
            $section = $phpWord->addSection([
                'marginTop' => 1000,
                'marginBottom' => 1000,
                'marginLeft' => 1000,
                'marginRight' => 1000
            ]);

            // ✅ ADD HEADER (will repeat on all pages)
            $header = $section->addHeader();
            $headerTable = $header->addTable(['width' => 9000, 'unit' => 'pct']);
            $row = $headerTable->addRow();
            $row->addCell(6000)->addText('YAYASAN IDEP', ['bold' => true, 'size' => 12]);
            $row->addCell(3000)->addText(date('Y'), ['size' => 10, 'alignment' => new Jc(Jc::RIGHT)]);
            $header->addLine(['color' => '1f4788', 'width' => 24]);

            // ✅ ADD FOOTER (will repeat on all pages)
            $footer = $section->addFooter();
            $footerTable = $footer->addTable(['width' => 9000, 'unit' => 'pct']);
            $footerRow = $footerTable->addRow();
            $footerRow->addCell(6000)->addText('Demosite Br. Medahan, Sukawati', ['size' => 9]);
            $footerRow->addCell(3000)->addText('Page {PAGE}', ['size' => 9, 'alignment' => new Jc(Jc::RIGHT)]);

            // Add content
            $sections[] = $section;
        }

        // Populate sections
        foreach ($sections as $index => $section) {
            $kegiatan = BTOR::getData($ids[$index]);
            $this->ensureRelationshipsLoaded($kegiatan);
            $this->addDocxHeader($section, $kegiatan);
            $this->addDocxContent($section, $kegiatan);
        }

        // Save and download
        // ... rest of export code ...
    }
}
```

---

## ⚠️ Important Notes

1. **{PAGE} and {NUMPAGES}** work automatically - no special code needed
2. **Headers/Footers repeat automatically** on every page
3. **Use tables in headers/footers** for better alignment and styling
4. **Margins** control space available for content
5. **Colors** must be in hex format without the # symbol

---

## 🎯 Quick Copy-Paste: Recommended Professional Setup

```php
$section = $phpWord->addSection([
    'marginTop' => 1000,
    'marginBottom' => 1000,
    'marginLeft' => 1440,
    'marginRight' => 1440
]);

// HEADER
$header = $section->addHeader();
$headerTable = $header->addTable(['width' => 9000, 'unit' => 'pct', 'borderSize' => 0]);
$headerRow = $headerTable->addRow();
$headerRow->addCell(3000)->addText('YAYASAN IDEP', ['bold' => true, 'size' => 12, 'color' => '1f4788']);
$headerRow->addCell(3000)->addText('BTOR', ['bold' => true, 'size' => 12, 'alignment' => new Jc(Jc::CENTER)]);
$headerRow->addCell(3000)->addText(date('Y'), ['size' => 10, 'alignment' => new Jc(Jc::RIGHT)]);
$header->addLine(['color' => '1f4788', 'width' => 24, 'height' => 2]);

// FOOTER
$footer = $section->addFooter();
$footerTable = $footer->addTable(['width' => 9000, 'unit' => 'pct', 'borderSize' => 0]);
$footerRow = $footerTable->addRow();
$footerRow->addCell(6000)->addText('Demosite Br. Medahan, Sukawati, Gianyar', ['size' => 9, 'italic' => true]);
$footerRow->addCell(3000)->addText('Page {PAGE}', ['size' => 9, 'alignment' => new Jc(Jc::RIGHT)]);
```

---

## 🧪 Testing

After adding headers/footers:

1. Export DOCX
2. Open in Word/Google Docs
3. Scroll through multiple pages
4. Headers/footers should appear on EVERY page ✅
5. Page numbers should increment correctly

---

**That's it!** Headers and footers will now appear on every page of your DOCX export. 🎉

# ######################################################################

# =============================

# Add Image to DOCX Header

## 🖼️ Quick Solution: Image from `public/images/uploads/header.png`

### **Option 1: Full-Width Image Header (SIMPLEST)**

```php
$header = $section->addHeader();

// Add your image
$imagePath = public_path('images/uploads/header.png');
if (file_exists($imagePath)) {
    $header->addImage($imagePath, [
        'width' => 600,      // Width in pixels
        'height' => 100,     // Height in pixels
        'alignment' => 'center'
    ]);
} else {
    // Fallback text if image not found
    $header->addText('YAYASAN IDEP', ['bold' => true, 'size' => 14]);
}

// Add line separator below image
$header->addLine(['color' => '1f4788', 'width' => 24, 'height' => 2]);
```

---

## 📝 Full Example: With Image Header

```php
public function exportDocx($id)
{
    $tmpDoc = null;
    try {
        $kegiatan = BTOR::getData($id);
        $this->ensureRelationshipsLoaded($kegiatan);

        $phpWord = new PhpWord();
        
        $section = $phpWord->addSection([
            'marginTop' => 1000,
            'marginBottom' => 1000,
            'marginLeft' => 1000,
            'marginRight' => 1000
        ]);

        // ✅ ADD HEADER WITH IMAGE
        $header = $section->addHeader();
        
        // Add image from public folder
        $imagePath = public_path('images/uploads/header.png');
        if (file_exists($imagePath)) {
            $header->addImage($imagePath, [
                'width' => 600,      // Adjust width as needed
                'height' => 100,     // Adjust height as needed
                'alignment' => 'center'
            ]);
        } else {
            // Fallback text
            $header->addText('YAYASAN IDEP - BACK TO OFFICE REPORT', ['bold' => true, 'size' => 12]);
        }
        
        // Add separator line
        $header->addLine(['color' => '1f4788', 'width' => 24, 'height' => 2]);

        // ✅ ADD FOOTER
        $footer = $section->addFooter();
        $footerTable = $footer->addTable(['width' => 9000, 'unit' => 'pct']);
        $footerRow = $footerTable->addRow();
        $footerRow->addCell(6000)->addText('Demosite Br. Medahan, Sukawati, Gianyar', ['size' => 9, 'italic' => true]);
        $footerRow->addCell(3000)->addText('Page {PAGE}', ['size' => 9, 'alignment' => new Jc(Jc::RIGHT)]);

        // ADD DOCUMENT CONTENT
        $this->addDocxHeader($section, $kegiatan);
        $this->addDocxContent($section, $kegiatan);

        // Save and download
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $tmpDoc = tempnam(sys_get_temp_dir(), 'btor_' . time() . '_');
        $phpWord->save($tmpDoc, 'Word2007');

        $filename = 'BTOR_' . $kegiatan->id . '_' . date('YmdHis') . '.docx';
        return response()->download($tmpDoc, $filename)
            ->deleteFileAfterSend(true);

    } catch (\Exception $e) {
        \Log::error('BTOR DOCX Export Error', ['error' => $e->getMessage()]);
        if ($tmpDoc && file_exists($tmpDoc)) {
            @unlink($tmpDoc);
        }
        return back()->with('error', 'Gagal mengekspor: ' . $e->getMessage());
    }
}
```

---

## 📐 Image Size Adjustments

**Width & Height are in pixels:**

```php
$header->addImage(public_path('images/uploads/header.png'), [
    'width' => 600,      // 600 pixels wide
    'height' => 100,     // 100 pixels tall
    'alignment' => 'center'
]);
```

**Common sizes:**

| Purpose | Width | Height |
|---------|-------|--------|
| Full-width header | 600-700 | 80-120 |
| Logo only | 100-150 | 80-100 |
| Small icon | 40-60 | 40-60 |

---

## 🎯 Option 2: Image + Text Side-by-Side

```php
$header = $section->addHeader();

$headerTable = $header->addTable(['width' => 9000, 'unit' => 'pct', 'borderSize' => 0]);
$row = $headerTable->addRow();

// Left: Image
$imageCell = $row->addCell(2500);
$imagePath = public_path('images/uploads/header.png');
if (file_exists($imagePath)) {
    $imageCell->addImage($imagePath, [
        'width' => 150,
        'height' => 80,
        'alignment' => 'left'
    ]);
}

// Right: Text info
$textCell = $row->addCell(6500);
$textCell->addText(
    'YAYASAN IDEP SELARAS ALAM',
    ['bold' => true, 'size' => 14]
);
$textCell->addText(
    'Back to Office Report',
    ['size' => 11, 'italic' => true]
);
$textCell->addText(
    'Year: ' . date('Y'),
    ['size' => 10]
);

// Add separator
$header->addLine(['color' => '1f4788', 'width' => 24]);
```

---

## ✅ Error Handling

The code above includes error handling:

```php
$imagePath = public_path('images/uploads/header.png');
if (file_exists($imagePath)) {
    $header->addImage($imagePath, [
        'width' => 600,
        'height' => 100,
        'alignment' => 'center'
    ]);
} else {
    // If image doesn't exist, show fallback text
    $header->addText('YAYASAN IDEP', ['bold' => true, 'size' => 14]);
}
```

This prevents errors if the image file is missing.

---

## 🔍 Troubleshooting

**Issue: Image not appearing**

1. Check file exists: `public/images/uploads/header.png`
2. Use full path: `public_path('images/uploads/header.png')`
3. Verify image format (PNG, JPG, GIF work fine)
4. Adjust width/height if image is too small/large

**Issue: Image looks blurry**

- Increase width/height values
- Use higher resolution image (2x size recommended)

**Issue: Header not repeating on all pages**

- Image should automatically appear on all pages when added to `$header = $section->addHeader()`

---

## 💡 Pro Tip: Dynamic Image Path

If your image path might change:

```php
$imageName = 'header.png';
$imagePath = public_path("images/uploads/{$imageName}");

if (file_exists($imagePath)) {
    $header->addImage($imagePath, [
        'width' => 600,
        'height' => 100,
        'alignment' => 'center'
    ]);
}
```

---

## 🎯 Recommended Setup for Your Project

```php
$header = $section->addHeader();

// Add header image
$headerImagePath = public_path('images/uploads/header.png');
if (file_exists($headerImagePath)) {
    $header->addImage($headerImagePath, [
        'width' => 650,
        'height' => 120,
        'alignment' => 'center'
    ]);
} else {
    $header->addText('YAYASAN IDEP SELARAS ALAM - BACK TO OFFICE REPORT', 
        ['bold' => true, 'size' => 12, 'alignment' => new Jc(Jc::CENTER)]);
}

// Add separator line
$header->addLine(['color' => '1f4788', 'width' => 24, 'height' => 2]);
```

---

**That's it!** Your header image will now appear on every page of the DOCX export. 🎉