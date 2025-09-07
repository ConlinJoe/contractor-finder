# Helper Snippets (Node + PHP)

These helpers inject variables into the seed prompt, call your model, and **validate** the JSON sidecar.

---

## Node (TypeScript/JavaScript)

```ts
type ReviewPoint = { text: string; citations: string[] };
type SourceLink = { site: string; url: string };

interface ReportJSON {
  contractor: string;
  city: string;
  state: string;
  last_updated: string;
  pros: ReviewPoint[];
  cons: ReviewPoint[];
  consistency_note?: string;
  recommended_due_diligence?: string[];
  sources: SourceLink[];
  schema_version?: string;
}

import fs from 'fs';

const seedPrompt = fs.readFileSync('./seed-prompt.md', 'utf8');

function fillTemplate(tpl: string, vars: Record<string, string>) {
  return tpl
    .replace(/{{contractor_name}}/g, vars.contractor_name)
    .replace(/{{city}}/g, vars.city)
    .replace(/{{state}}/g, vars.state)
    .replace(/{{today_iso}}/g, vars.today_iso);
}

function extractJsonSidecar(output: string): ReportJSON {
  const match = output.match(/```json\\s*([\\s\\S]*?)\\s*```/);
  if (!match) throw new Error('JSON sidecar not found');
  const parsed = JSON.parse(match[1]);
  // Minimal validation
  if (!parsed.contractor || !parsed.city || !parsed.state) throw new Error('Missing basic fields');
  if (!Array.isArray(parsed.pros) || !Array.isArray(parsed.cons)) throw new Error('Pros/Cons must be arrays');
  for (const arr of [parsed.pros, parsed.cons]) {
    for (const item of arr) {
      if (typeof item.text !== 'string' || !Array.isArray(item.citations)) {
        throw new Error('Each pro/con needs text + citations[]');
      }
    }
  }
  return parsed as ReportJSON;
}

// Example runtime
export async function buildPromptAndCallModel(run: (prompt: string) => Promise<string>) {
  const vars = {
    contractor_name: 'Big Bully Turf',
    city: 'San Diego',
    state: 'CA',
    today_iso: new Date().toISOString().slice(0,10),
  };
  const prompt = fillTemplate(seedPrompt, vars);
  const modelOutput = await run(prompt);
  const json = extractJsonSidecar(modelOutput);

  // Optional: enforce link parity (ensure citations appear in markdown too)
  // app-specific checks can go here

  return { markdown: modelOutput.replace(/```json[\\s\\S]*?```/,'').trim(), json };
}
```

---

## PHP

```php
<?php
function fill_template($tpl, $vars) {
  $search = array('{{contractor_name}}','{{city}}','{{state}}','{{today_iso}}');
  $replace = array($vars['contractor_name'], $vars['city'], $vars['state'], $vars['today_iso']);
  return str_replace($search, $replace, $tpl);
}

function extract_json_sidecar($output) {
  if (!preg_match('/```json\\s*([\\s\\S]*?)\\s*```/m', $output, $m)) {
    throw new Exception('JSON sidecar not found');
  }
  $json = json_decode($m[1], true);
  if (!$json) throw new Exception('Invalid JSON sidecar');
  // Minimal validation
  if (empty($json['contractor']) || empty($json['city']) || empty($json['state'])) {
    throw new Exception('Missing basic fields');
  }
  if (!isset($json['pros']) || !is_array($json['pros']) || !isset($json['cons']) || !is_array($json['cons'])) {
    throw new Exception('Pros/Cons must be arrays');
  }
  foreach (['pros','cons'] as $k) {
    foreach ($json[$k] as $item) {
      if (!isset($item['text']) || !is_string($item['text']) || !isset($item['citations']) || !is_array($item['citations'])) {
        throw new Exception('Each pro/con needs text + citations[]');
      }
    }
  }
  return $json;
}

// Example runtime
$seed_prompt = file_get_contents(__DIR__ . '/seed-prompt.md');
$vars = [
  'contractor_name' => 'Big Bully Turf',
  'city' => 'San Diego',
  'state' => 'CA',
  'today_iso' => date('Y-m-d')
];
$prompt = fill_template($seed_prompt, $vars);

// Call your model with $prompt...
// $model_output = call_model($prompt);

function strip_json_from_markdown($md) {
  return preg_replace('/```json[\\s\\S]*?```/m', '', $md);
}

// $json = extract_json_sidecar($model_output);
// $markdown_without_json = trim(strip_json_from_markdown($model_output));
?>
```
