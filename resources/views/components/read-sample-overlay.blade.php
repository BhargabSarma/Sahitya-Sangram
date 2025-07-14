<div id="sample-overlay"
     style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(36,38,43,0.86); z-index:9999; align-items:center; justify-content:center;">
    <div style="position:relative; background:#fff; border-radius:16px; box-shadow:0 8px 32px rgba(40,44,52,.14); max-width:1040px; width:98vw; max-height:92vh; margin:auto; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:0;">
        <div class="book-header" style="display:flex; justify-content:space-between; align-items:center; padding:16px 24px; background:#fff; box-shadow:0 2px 4px rgba(0,0,0,0.06); position:sticky; top:0; z-index:100; border-radius:16px 16px 0 0;">
            <div class="book-title" style="font-size:1.5rem;font-weight:600;color:#333; margin-right:24px;">
                {{ $book->title ?? '' }} <span style="font-weight:400;color:#ff9800;font-size:1rem;">(Sample)</span>
            </div>
            <button class="close-btn"
                id="close-sample-btn"
                style="font-size:1.4rem;color:#999;background:none;border:none;cursor:pointer;transition:color 0.2s ease;"
                aria-label="Close">&times;</button>
        </div>
        <div id="book-container" style="display:flex; justify-content:center; margin:20px auto 40px auto; padding:0 10px;">
            <div id="flipbook" style="width:1000px; height:700px; box-shadow:0 5px 20px rgba(0,0,0,0.15); background:#fff;">
                @foreach($samplePages as $p)
                    <div class="page" style="background:#fff; position:relative; overflow:hidden; padding:0; margin:0;">
                        <div class="page-content" style="width:100%; height:100%; padding:0; margin:0; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                            @if(!empty($p['image']))
                                <img src="{{ $p['image'] }}" alt="Sample Page {{ $p['number'] }}" style="width:100%; height:100%; object-fit:contain;">
                            @else
                                {!! nl2br(e($p['content'] ?? 'Sample Page ' . $p['number'])) !!}
                            @endif
                            <div class="page-number" style="position:absolute; bottom:10px; right:16px; font-size:0.85rem; color:#aaa;">{{ $p['number'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>