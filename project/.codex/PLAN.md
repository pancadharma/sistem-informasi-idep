# Project Plan

This living plan tracks export/print/dashboard tasks. Update it as features ship.

## Dashboard Export & Print Improvements

- [x] Add export endpoint and controller for PDF/DOCX
- [x] Include charts and map snapshots in exports
- [x] Add Google Static Maps fallback when canvas capture fails
- [x] Make PDF template match AdminLTE style (cards, table, footer)
- [x] Add totals footer to "Table Desa Penerima Manfaat"
- [x] Ensure DataTable prints without pagination, search, or length controls
- [x] Include kabupaten pie chart in DOCX export
- [x] Add icons to export PDF/DOCX stat blocks
- [x] Use program/provinsi names (not IDs) in export header
- [ ] Optionally switch PDF orientation to portrait to match printed view

## Notes

- Keep `GOOGLE_MAPS_API_KEY` set for Static Maps fallback.
- If map snapshots still fail, consider capturing a Static Map with markers representing current filtered data.
