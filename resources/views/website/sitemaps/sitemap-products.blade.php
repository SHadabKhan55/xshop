@cache('sitemap_products'. cacheNumber(), 3600)
{{--update every 1 hour--}}
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"

         xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"

         xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @if(\App\Models\Product::count() > 0)

        <url>
            <loc>{{route('client.products')}}</loc>
            <lastmod>{{\App\Models\Product::orderByDesc('updated_at')->first()->updated_at->tz('UTC')->toAtomString()}}</lastmod>
            <changefreq>daily</changefreq>
            <priority>1</priority>
        </url>
    @endif
    @foreach(\App\Models\Product::where('status',1)->orderBy('id')->get(['slug','updated_at']) as $item)

        <url>
            <loc>{{route('client.product',$item->slug)}}</loc>
            <lastmod>{{$item->updated_at->tz('UTC')->toAtomString()}}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.5</priority>
        </url>
    @endforeach
    @if(config('app.xlang.active'))
        @foreach(\App\Models\XLang::where('is_default',0)->pluck('tag')->toArray() as $lang)

            @php(app()->setLocale($lang))
            @if(\App\Models\Product::count() > 0)

                <url>
                    <loc>{{route('client.products')}}/{{$lang}}</loc>
                    <lastmod>{{\App\Models\Product::orderByDesc('updated_at')->first()->updated_at->tz('UTC')->toAtomString()}}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1</priority>
                </url>
            @endif
            @foreach(\App\Models\Product::where('status',1)->whereLocale('name',$lang)->orderBy('id')->get(['slug','updated_at','name']) as $item)

                    <url>
                        <loc>{{$item->webUrl()}}</loc>
                        <lastmod>{{$item->updated_at->tz('UTC')->toAtomString()}}</lastmod>
                        <changefreq>weekly</changefreq>
                        <priority>0.5</priority>
                    </url>
            @endforeach
        @endforeach
    @endif

</urlset>
@endcache