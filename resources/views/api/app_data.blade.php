@if(Request::get("android") && !Request::get("ios"))
<div class="row appContent" itemscope itemtype="http://schema.org/SoftwareApplication">
<div class="col-1"2>
<h3 class="text-center text-sm-start" itemprop="name">{{$android->product_info->title}}</h3>
</div>
<div class="col-12 col-md-4 text-center"><img src={{$thumbnail}} alt="{{$android->product_info->title}}" class="img-fluid appIcon" itemprop="image">
</div>
<div class="col-12 col-md-8">
<div class="row">
<div class="col-5 col-md-4"><strong>Author:</strong></div>
<div class="col-7 col-md-8" itemprop="author">{{ $android->product_info->authors[0]->name }}</div>
</div>
<div class="row">
<div class="col-5 col-md-4"><strong>Platforms:</strong></div>
<div class="col-7 col-md-8">
<link itemprop=operatingSystem href=http://schema.org/Android>
<a href="{{$android->search_metadata->google_play_product_url}}" target=_blank>Android</a>
</div>
</div>
<div class="row">
<div class="col-5 col-md-4"><strong>Last Updated:</strong></div>
<div class="col-7 col-md-8" itemprop="dateModified"><p class="mb-1">{{$android->updated_on}}</p></div>
</div>
<div class="row">
<div class="col-5 col-md-4">
<strong>Rating:</strong><img src="https://mobilekishop.net/blog/wp-content/uploads/2024/05/star-rating-icon.png"alt="android-icon" width="24" height="24">
</div>
@php $reviews = $android->product_info->reviews; if($reviews >= 1000000) { $reviews = number_format($reviews / 1000000, 1) . 'M'; } elseif($reviews >= 1000) { $reviews = number_format($reviews / 1000, 1) . 'K'; } else { $reviews = $reviews; } $reviews = str_replace('.0', '', $reviews); @endphp
<div class="col-7 col-md-8" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><i class="fas fa-star text-warning"></i><span itemprop="ratingValue">{{ $android->product_info->rating }}</span><small itemprop="reviewCount">({{$reviews}} Ratings)</small><meta itemprop="bestRating" content=5><span>{{$android->product_info->downloads}} Downloads</span>
</div>
</div>
<div class="row">
<div class="col-5 col-md-4"><strong>Content:</strong></div>
<div class="col-7 col-md-8">
<img src="{{$android->product_info->content_rating->thumbnail}}" alt="content_rating" width="16" height="16" itemprop="contentRating">
<span class="text-muted">{{$android->product_info->content_rating->text}}</span>
</div>
</div>
@if(isset($android->product_info->extensions[0]))
<div class="row">
<div class="col-5 col-md-4"><strong>Plan:</strong></div>
<div class="col-7 col-md-8"><p class="mb-1">{{$android->product_info->extensions[0]}}</p></div>
</div>
@endif
</div>
<div class="text-center mt-3">
<img src={{$screenshots}} alt="{{$android->product_info->title}}-screenshots" class="img-fluid">
</div>
<p class="card-text mt-3">{!! isset($android->about_this_app->snippet) ? $android->about_this_app->snippet : '' !!}</p>
</div>


@elseif(!Request::get("android") && Request::get("ios"))

<div class="row appContent" itemscope itemtype="http://schema.org/SoftwareApplication">
    <div class="col-12">
        <h3 class="text-center text-sm-start" itemprop="name">{{$ios->title}}</h3>
        @if(isset($ios->snippet))
        <p>{{$ios->snippet}}</p>
        @endif
    </div>
    <div class="col-12 col-md-4 text-center">
        <img src="{{$thumbnail}}" alt="{{$ios->title}}" class="img-fluid appIcon" itemprop="image">
    </div>
    <div class="col-12 col-md-8">
        <div class="row">
            <div class="col-5 col-md-4"><strong>Author:</strong></div>
            <div class="col-7 col-md-8" itemprop="author">{{ $ios->developer->name }}</div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4"><strong>Platforms:</strong></div>
            <div class="col-7 col-md-8">
                <link itemprop="operatingSystem" href="http://schema.org/iOS"/>
                <a href="{{Request::get("ios")}}" target="_blank">iOS</a>
            </div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4"><strong>Last Updated:</strong></div>
            <div class="col-7 col-md-8" itemprop="dateModified"><p class="mb-1">{{$ios->version_history[0]->release_date}}</p></div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4">
                <strong>Rating:</strong><img src="https://mobilekishop.net/blog/wp-content/uploads/2023/06/Apple-Icon-1.png" alt="apple-icon" width="24" height="24">
            </div>
            <div class="col-7 col-md-8" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><i class="fas fa-star text-warning"></i><span class="text-muted" itemprop="ratingValue">{{$ios->rating}}</span><small itemprop="reviewCount">({{$ios->rating_count}})</small><meta itemprop="bestRating" content="5"/>
            </div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4"><strong>Content:</strong></div>
            <div class="col-7 col-md-8"><span class="text-muted" itemprop="contentRating">{{$ios->age_rating}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4"><strong>Plan:</strong></div>
            <div class="col-7 col-md-8"><p class="mb-1">{{$ios->price}} {{isset($ios->in_app_purchases) ? $ios->in_app_purchases : null}}</p></div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4"><strong>Language:</strong></div>
            <div class="col-7 col-md-8"><p class="mb-1 text-uppercase">{{implode(", ", $ios->information->supported_languages)}}</p></div>
        </div>
    </div>
    <div class="text-center mt-3">
        <img src="{{$screenshots}}" alt="{{$ios->title}}-screenshots" class="img-fluid">
    </div>
    <p class="card-text mt-3" itemprop="description">{!! $ios->description !!}</p>
</div>


@elseif(Request::get("android") && Request::get("ios"))
<div class="row appContent" itemscope itemtype="http://schema.org/SoftwareApplication">
    <div class="col-12">
        <h3 class="text-center text-sm-start" itemprop="name">{{$android->product_info->title}}</h3>
        @if(isset($ios->snippet))
        <p>{{$ios->snippet}}</p>
        @endif
    </div>
    <div class="col-12 col-md-4 text-center">
        <img src="{{$thumbnail}}" alt="{{$android->product_info->title}}" class="img-fluid appIcon" itemprop="image">
    </div>
    <div class="col-12 col-md-8">
        <div class="row">
            <div class="col-5 col-md-4"><strong>Author:</strong></div>
            <div class="col-7 col-md-8" itemprop="author">{{ $android->product_info->authors[0]->name }}</div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4"><strong>Platforms:</strong></div>
            <div class="col-7 col-md-8">
                <link itemprop="operatingSystem" href="http://schema.org/Android"/>
                <link itemprop="operatingSystem" href="http://schema.org/iOS"/>
                <a href="{{$android->search_metadata->google_play_product_url}}" target="_blank">Android</a>, <a href="{{Request::get("ios")}}" target="_blank">iOS</a>
            </div>
        </div>
        <div class="row">
            <div class="col-5 col-md-4"><strong>Last Updated:</strong></div>
            <div class="col-7 col-md-8" itemprop="dateModified"><p class="mb-1">{{$android->updated_on}}</p></div>
        </div>
        
        <div class="row">
            <div class="col-5 col-md-4">
                <strong>Android Rating:</strong><img src="https://mobilekishop.net/blog/wp-content/uploads/2023/06/Android-Icon-1.png" alt="android-icon" width="24" height="24"> 
            </div>
            @php
            $android_reviews = $android->product_info->reviews;
            if($android_reviews >= 1000000) {
                $android_reviews = number_format($android_reviews / 1000000, 1) . 'M';
            } elseif($android_reviews >= 1000) {
                $android_reviews = number_format($android_reviews / 1000, 1) . 'K';
            } else {
                $android_reviews = $android_reviews;
            }
            $android_reviews = str_replace('.0', '', $android_reviews);
            @endphp
            <div class="col-7 col-md-8" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                <i class="fas fa-star text-warning"></i><span class="text-muted" itemprop="ratingValue">{{ $android->product_info->rating }}</span><small itemprop="reviewCount">({{$android_reviews}} Ratings)</small><meta itemprop="bestRating" content="5"/><span>{{$android->product_info->downloads}} Downloads</span>
            </div>
        </div>
        
        <div class="row">
            <div class="col-5 col-md-4">
                <strong>iOS Rating:</strong><img src="https://mobilekishop.net/blog/wp-content/uploads/2023/06/Apple-Icon-1.png" alt="apple-icon" width="24" height="24">
            </div>
            <div class="col-7 col-md-8">
                <i class="fas fa-star text-warning"></i><span class="text-muted">{{$ios->rating}} ({{$ios->rating_count}})</span>
            </div>
        </div>
        
        <div class="row">
            <div class="col-5 col-md-4"><strong>Content:</strong></div>
            <div class="col-7 col-md-8">
                <img src="{{$android->product_info->content_rating->thumbnail}}" alt="content_rating" width="16" height="16" itemprop="contentRating"><span class="text-muted">{{$android->product_info->content_rating->text}}</span>
            </div>
        </div>
        
        @if(isset($android->product_info->extensions[0]))
        <div class="row">
            <div class="col-5 col-md-4"><strong>Plan:</strong></div>
            <div class="col-7 col-md-8"><p class="mb-1">{{$android->product_info->extensions[0]}} {{isset($ios->in_app_purchases) ? $ios->in_app_purchases : null}}</p></div>
        </div>
        @endif
        
        <div class="row">
            <div class="col-5 col-md-4"><strong>Language:</strong></div>
            <div class="col-7 col-md-8"><p class="mb-1 text-uppercase">{{implode(", ", $ios->information->supported_languages)}}</p></div>
        </div>
    </div>
    <div class="text-center mt-3">
        <img src="{{$screenshots}}" alt="{{$android->product_info->title}}-screenshots" class="img-fluid">
    </div>
    <p class="card-text mt-3">{!! isset($android->about_this_app->snippet) ? $android->about_this_app->snippet : '' !!}</p>
</div>

@endif