# JSON Sidecar Spec (to accompany the markdown report)

Return this JSON **after** the markdown report, wrapped in triple backticks and marked as `json`.  
All fields must reflect the same content as the markdown section.

```json
{
  "contractor": "{{contractor_name}}",
  "city": "{{city}}",
  "state": "{{state}}",
  "last_updated": "{{today_iso}}",
  "pros": [
    {"text": "Example pro...", "citations": ["https://...","https://..."]}
  ],
  "cons": [
    {"text": "Example con...", "citations": ["https://..."]}
  ],
  "consistency_note": "e.g., Mixed experiences; communication issues cluster in 2024–2025 reviews.",
  "recommended_due_diligence": [
    "Ask for written warranty response times.",
    "Request seam layout diagram for large areas."
  ],
  "sources": [
    {"site": "Yelp", "url": "https://..."},
    {"site": "BBB", "url": "https://..."}
  ]
}
```

## Rules
- **Parity:** `pros`, `cons`, and `sources` must match the markdown content.
- **Citations:** Each list item’s `citations` must be the exact URLs used in the bullets.
- **Lengths:** Keep strings concise; avoid long quotes.
- **Dates:** `last_updated` should be an ISO date (YYYY-MM-DD).
- **Extensibility:** You may add fields, but do not remove any of the above without versioning.
- **Versioning (optional):** Add `"schema_version": "1.0.0"` if you plan to evolve this contract.
