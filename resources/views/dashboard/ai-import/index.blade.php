@extends('layouts.dashboard')
@section('title', 'AI Product Import')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1><i class="fas fa-robot" style="color:var(--admin-accent);margin-right:8px;"></i>AI Product Import</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                AI Import
            </div>
        </div>
    </div>

    @include('includes.info-bar')

    {{-- Step 1: Generate Prompt --}}
    <div class="admin-card" style="margin-bottom:24px;">
        <div class="admin-card-header">
            <h2><span class="ai-step-badge">1</span> Generate Prompt</h2>
        </div>
        <div class="admin-card-body">
            <p style="color:var(--admin-text-secondary);margin-bottom:16px;">
                Enter the phone name below and click <strong>Copy Prompt</strong>. Then paste it into
                <a href="https://gemini.google.com/" target="_blank" style="color:var(--admin-accent);">Google Gemini</a>.
            </p>
            <div style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
                <div class="admin-form-group" style="flex:1;min-width:250px;margin-bottom:0;">
                    <label class="admin-form-label">Phone Name</label>
                    <input type="text" id="phoneName" class="admin-form-control" placeholder="e.g. Samsung Galaxy S25 Ultra"
                        autofocus>
                </div>
                <button type="button" id="copyPromptBtn" class="btn-admin-primary" style="height:42px;white-space:nowrap;">
                    <i class="fas fa-copy"></i> Copy Prompt
                </button>
            </div>
            <div id="promptCopied" style="display:none;margin-top:12px;padding:10px 16px;background:var(--admin-success-bg, #d1fae5);
                    color:var(--admin-success, #059669);border-radius:8px;font-weight:500;">
                <i class="fas fa-check-circle"></i> Prompt copied to clipboard! Now paste it into Gemini.
            </div>

            {{-- Collapsible prompt preview --}}
            <details style="margin-top:16px;">
                <summary style="cursor:pointer;color:var(--admin-text-secondary);font-size:13px;">
                    <i class="fas fa-eye"></i> Preview prompt template
                </summary>
                <pre id="promptPreview" style="margin-top:8px;padding:16px;background:var(--admin-bg-secondary);
                        border-radius:8px;font-size:12px;white-space:pre-wrap;word-break:break-word;
                        max-height:300px;overflow-y:auto;border:1px solid var(--admin-surface-border);"></pre>
            </details>
        </div>
    </div>

    {{-- Step 2: Paste JSON --}}
    <div class="admin-card" style="margin-bottom:24px;">
        <div class="admin-card-header">
            <h2><span class="ai-step-badge">2</span> Paste Gemini Response</h2>
        </div>
        <div class="admin-card-body">
            <p style="color:var(--admin-text-secondary);margin-bottom:16px;">
                Copy the JSON response from Gemini and paste it below. Then click <strong>Preview</strong>.
            </p>
            <div class="admin-form-group" style="margin-bottom:16px;">
                <textarea id="jsonInput" class="admin-form-control" rows="12"
                    placeholder='Paste the JSON response from Gemini here...'
                    style="font-family:monospace;font-size:13px;"></textarea>
            </div>
            <div style="display:flex;gap:12px;">
                <button type="button" id="previewBtn" class="btn-admin-primary">
                    <i class="fas fa-search"></i> Preview
                </button>
                <button type="button" id="clearBtn" class="btn-admin-secondary">
                    <i class="fas fa-eraser"></i> Clear
                </button>
            </div>
            <div id="jsonError" style="display:none;margin-top:12px;padding:10px 16px;background:#fef2f2;
                    color:#dc2626;border-radius:8px;font-weight:500;">
            </div>
        </div>
    </div>

    {{-- Step 3: Preview & Save --}}
    <div id="previewSection" style="display:none;">
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header" style="display:flex;justify-content:space-between;align-items:center;">
                <h2><span class="ai-step-badge">3</span> Preview & Save</h2>
                <div>
                    <span id="updateBadge" class="admin-badge badge-warning" style="display:none;font-size:13px;">
                        <i class="fas fa-edit"></i> UPDATE EXISTING
                    </span>
                    <span id="createBadge" class="admin-badge badge-success" style="display:none;font-size:13px;">
                        <i class="fas fa-plus"></i> NEW PRODUCT
                    </span>
                </div>
            </div>
            <div class="admin-card-body">
                {{-- Basic Info --}}
                <div style="margin-bottom:24px;">
                    <h3 style="margin-bottom:12px;color:var(--admin-text-primary);">
                        <i class="fas fa-info-circle" style="color:var(--admin-accent);margin-right:6px;"></i>Basic Info
                    </h3>
                    <div class="admin-table-wrap">
                        <table class="admin-table" id="basicInfoTable">
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- Specifications --}}
                <div style="margin-bottom:24px;">
                    <h3 style="margin-bottom:12px;color:var(--admin-text-primary);">
                        <i class="fas fa-microchip" style="color:var(--admin-accent);margin-right:6px;"></i>Specifications
                        <span id="specCount" style="color:var(--admin-text-muted);font-size:13px;font-weight:400;"></span>
                    </h3>
                    <div class="admin-table-wrap">
                        <table class="admin-table" id="specTable">
                            <thead>
                                <tr>
                                    <th style="width:30%;">Attribute</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- Prices --}}
                <div style="margin-bottom:24px;">
                    <h3 style="margin-bottom:12px;color:var(--admin-text-primary);">
                        <i class="fas fa-tags" style="color:var(--admin-accent);margin-right:6px;"></i>Prices
                        <span id="priceCount" style="color:var(--admin-text-muted);font-size:13px;font-weight:400;"></span>
                    </h3>
                    <div class="admin-table-wrap">
                        <table class="admin-table" id="priceTable">
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th>Code</th>
                                    <th>Currency</th>
                                    <th style="text-align:right;">Price</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- Expert Rating --}}
                <div id="expertSection" style="margin-bottom:24px;display:none;">
                    <h3 style="margin-bottom:12px;color:var(--admin-text-primary);">
                        <i class="fas fa-star" style="color:#f59e0b;margin-right:6px;"></i>Expert Rating
                    </h3>
                    <div class="admin-table-wrap">
                        <table class="admin-table" id="expertTable">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th style="text-align:center;">Score</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- Save Button --}}
                <div style="margin-top:24px;padding-top:24px;border-top:1px solid var(--admin-surface-border);">
                    <button type="button" id="saveBtn" class="btn-admin-primary"
                        style="width:100%;padding:14px;font-size:16px;">
                        <i class="fas fa-save"></i> Save to Database
                    </button>
                </div>

                <div id="saveResult" style="display:none;margin-top:16px;padding:16px;border-radius:8px;"></div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .ai-step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--admin-accent);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            margin-right: 10px;
        }

        #specTable tbody tr:hover,
        #priceTable tbody tr:hover {
            background: var(--admin-hover-bg, rgba(99, 102, 241, 0.04));
        }

        .score-bar {
            height: 8px;
            border-radius: 4px;
            background: var(--admin-bg-secondary);
            overflow: hidden;
            width: 120px;
            display: inline-block;
            vertical-align: middle;
            margin-left: 8px;
        }

        .score-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .btn-admin-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ========== PROMPT GENERATION ==========
            const specFields = @json($attributes->pluck('name'));
            const countryCodes = @json($countries->pluck('country_code'));

            function buildPrompt(phoneName) {
                return `You are a mobile phone database assistant. I need structured data for the phone: "${phoneName}".

    Return ONLY a valid JSON object (no markdown, no explanation) with this exact structure:

    {
      "name": "${phoneName}",
      "brand": "<brand name, e.g. Samsung>",
      "category": "mobile-phones",
      "release_date": "<YYYY-MM-DD or null>",
      "specifications": {
    ${specFields.map(f => `    "${f}": "<value or null>"`).join(',\n')}
      },
      "prices": {
    ${countryCodes.map(c => `    "${c}": <price_in_local_currency_integer_or_0>`).join(',\n')}
      },
      "expert_rating": {
        "design": <0-10>,
        "display": <0-10>,
        "performance": <0-10>,
        "camera": <0-10>,
        "battery": <0-10>,
        "value_for_money": <0-10>,
        "verdict": "<one-line summary>",
        "rated_by": "MobileKiShop"
      }
    }

    Rules:
    - Return ONLY the JSON, no text before or after
    - For prices, use the local currency integer value (e.g. PKR for pk, USD for us, AED for ae). Use 0 if unknown
    - For specifications, use "null" string if data is not available
    - ram_in_gb and rom_in_gb should be just the number (e.g. "8", "256")
    - screen_size should be just the number in inches (e.g. "6.7")
    - no_of_cameras should be the total number of rear cameras (e.g. "3")
    - no_of_sims should be just the number (e.g. "2")
    - operating_system should be lowercase (e.g. "android", "ios")`;
            }

            document.getElementById('copyPromptBtn').addEventListener('click', function () {
                const name = document.getElementById('phoneName').value.trim();
                if (!name) {
                    document.getElementById('phoneName').focus();
                    return;
                }
                const prompt = buildPrompt(name);
                navigator.clipboard.writeText(prompt).then(() => {
                    document.getElementById('promptCopied').style.display = 'block';
                    document.getElementById('promptPreview').textContent = prompt;
                    setTimeout(() => {
                        document.getElementById('promptCopied').style.display = 'none';
                    }, 5000);
                });
            });

            // Update preview when phone name changes
            document.getElementById('phoneName').addEventListener('input', function () {
                const name = this.value.trim() || 'Phone Name';
                document.getElementById('promptPreview').textContent = buildPrompt(name);
            });

            // ========== PREVIEW ==========
            document.getElementById('previewBtn').addEventListener('click', function () {
                const raw = document.getElementById('jsonInput').value.trim();
                if (!raw) return;

                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                document.getElementById('jsonError').style.display = 'none';

                fetch("{{ route('dashboard.ai-import.process') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ json_data: raw }),
                })
                    .then(r => r.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-search"></i> Preview';

                        if (!data.success) {
                            document.getElementById('jsonError').style.display = 'block';
                            document.getElementById('jsonError').innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.error;
                            document.getElementById('previewSection').style.display = 'none';
                            return;
                        }

                        renderPreview(data);
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-search"></i> Preview';
                        document.getElementById('jsonError').style.display = 'block';
                        document.getElementById('jsonError').innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + err.message;
                    });
            });

            function renderPreview(data) {
                const p = data.preview;
                document.getElementById('previewSection').style.display = 'block';

                // Badges
                if (data.is_update) {
                    document.getElementById('updateBadge').style.display = 'inline-flex';
                    document.getElementById('createBadge').style.display = 'none';
                } else {
                    document.getElementById('updateBadge').style.display = 'none';
                    document.getElementById('createBadge').style.display = 'inline-flex';
                }

                // Basic Info
                let basicHtml = '';
                basicHtml += `<tr><td style="font-weight:600;width:30%;">Name</td><td>${escHtml(p.name)}</td></tr>`;
                basicHtml += `<tr><td style="font-weight:600;">Slug</td><td><code>${escHtml(p.slug)}</code></td></tr>`;
                basicHtml += `<tr><td style="font-weight:600;">Brand</td><td>${p.brand ? escHtml(p.brand.name) : '<span style="color:var(--admin-danger);">Not found</span>'}</td></tr>`;
                basicHtml += `<tr><td style="font-weight:600;">Category</td><td>${p.category ? escHtml(p.category.name) : '—'}</td></tr>`;
                basicHtml += `<tr><td style="font-weight:600;">Release Date</td><td>${p.release_date || '—'}</td></tr>`;
                document.querySelector('#basicInfoTable tbody').innerHTML = basicHtml;

                // Specs
                let specHtml = '';
                if (p.specifications && p.specifications.length) {
                    p.specifications.forEach(s => {
                        specHtml += `<tr><td style="font-weight:500;">${escHtml(s.label)}</td><td>${escHtml(s.value)}</td></tr>`;
                    });
                    document.getElementById('specCount').textContent = `(${p.specifications.length} fields)`;
                }
                document.querySelector('#specTable tbody').innerHTML = specHtml || '<tr><td colspan="2">No specifications found</td></tr>';

                // Prices
                let priceHtml = '';
                if (p.prices && p.prices.length) {
                    p.prices.forEach(pr => {
                        priceHtml += `<tr>
                        <td>${escHtml(pr.country_name)}</td>
                        <td><code>${pr.country_code.toUpperCase()}</code></td>
                        <td>${escHtml(pr.currency)}</td>
                        <td style="text-align:right;font-weight:600;color:var(--admin-success);">${Number(pr.price).toLocaleString()}</td>
                    </tr>`;
                    });
                    document.getElementById('priceCount').textContent = `(${p.prices.length} countries)`;
                }
                document.querySelector('#priceTable tbody').innerHTML = priceHtml || '<tr><td colspan="4">No prices found</td></tr>';

                // Expert Rating
                const er = p.expert_rating;
                if (er) {
                    document.getElementById('expertSection').style.display = 'block';
                    const fields = ['design', 'display', 'performance', 'camera', 'battery', 'value_for_money'];
                    let expertHtml = '';
                    let total = 0, count = 0;
                    fields.forEach(f => {
                        if (er[f] !== undefined) {
                            const score = parseFloat(er[f]);
                            total += score;
                            count++;
                            const color = score >= 8 ? '#22c55e' : score >= 6 ? '#3b82f6' : score >= 4 ? '#f59e0b' : '#ef4444';
                            expertHtml += `<tr>
                            <td style="font-weight:500;text-transform:capitalize;">${f.replace('_', ' ')}</td>
                            <td style="text-align:center;">
                                <span style="font-weight:700;color:${color};">${score}</span>/10
                                <div class="score-bar"><div class="score-bar-fill" style="width:${score * 10}%;background:${color};"></div></div>
                            </td>
                        </tr>`;
                        }
                    });
                    if (count > 0) {
                        const overall = (total / count).toFixed(1);
                        const oColor = overall >= 8 ? '#22c55e' : overall >= 6 ? '#3b82f6' : '#f59e0b';
                        expertHtml += `<tr style="border-top:2px solid var(--admin-surface-border);">
                        <td style="font-weight:700;">Overall</td>
                        <td style="text-align:center;font-weight:800;font-size:18px;color:${oColor};">${overall}/10</td>
                    </tr>`;
                    }
                    if (er.verdict) {
                        expertHtml += `<tr><td style="font-weight:500;">Verdict</td><td><em>${escHtml(er.verdict)}</em></td></tr>`;
                    }
                    document.querySelector('#expertTable tbody').innerHTML = expertHtml;
                } else {
                    document.getElementById('expertSection').style.display = 'none';
                }

                // Scroll to preview
                document.getElementById('previewSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // ========== SAVE ==========
            document.getElementById('saveBtn').addEventListener('click', function () {
                const raw = document.getElementById('jsonInput').value.trim();
                if (!raw) return;

                if (!confirm('Are you sure you want to save this product to the database?')) return;

                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                fetch("{{ route('dashboard.ai-import.save') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ json_data: raw }),
                })
                    .then(r => r.json())
                    .then(data => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save"></i> Save to Database';

                        const resultDiv = document.getElementById('saveResult');
                        resultDiv.style.display = 'block';

                        if (data.success) {
                            resultDiv.style.background = '#d1fae5';
                            resultDiv.style.color = '#059669';
                            resultDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}
                        <br><a href="${data.edit_url}" style="color:#059669;font-weight:600;margin-top:8px;display:inline-block;">
                        <i class="fas fa-edit"></i> Edit Product</a>`;
                        } else {
                            resultDiv.style.background = '#fef2f2';
                            resultDiv.style.color = '#dc2626';
                            resultDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${data.error}`;
                        }
                    })
                    .catch(err => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-save"></i> Save to Database';
                        const resultDiv = document.getElementById('saveResult');
                        resultDiv.style.display = 'block';
                        resultDiv.style.background = '#fef2f2';
                        resultDiv.style.color = '#dc2626';
                        resultDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${err.message}`;
                    });
            });

            // ========== CLEAR ==========
            document.getElementById('clearBtn').addEventListener('click', function () {
                document.getElementById('jsonInput').value = '';
                document.getElementById('previewSection').style.display = 'none';
                document.getElementById('jsonError').style.display = 'none';
                document.getElementById('saveResult').style.display = 'none';
            });

            // ========== HELPERS ==========
            function escHtml(str) {
                if (!str) return '';
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }
        });
    </script>
@endsection