import(common.tpl)

/* Open Graph protocol */

title = $title

meta[property="og:title]|content = $title

meta[property="og:locale"]|content = $seo['locale']
meta[property="og:type"]|content = $seo['type']
meta[property="og:title"]|content = $seo['title']
meta[property="og:description"]|content = $seo['description']
meta[property="og:url"]|content = $seo['url']
meta[property="og:site_name"]|content = $seo['site_name']

meta[property="article:author"]|content = $seo['author']
meta[property="article:published_time"]|content = $seo['published_time']
meta[property="article:modified_time"]|content = $seo['modified_time']


meta[property="og:image"]|content = $seo['image']
meta[property="og:image:width"]|content = $seo['image:width']
meta[property="og:image:height"]|content = $seo['image:height']
meta[property="og:image:type"]|content = $seo['image:type']

meta[name="author"]|content = $seo['author']

/* twitter */

meta[name="twitter:card"]|content = $seo['twitter']['card']
meta[name="twitter:creator"]|content = $seo['twitter']['creator']
meta[name="twitter:site"]|content = $seo['twitter']['site']
meta[name="twitter:label1"]|content = $seo['twitter']['label1']
meta[name="twitter:data1"]|content = $seo['twitter']['data1']
meta[name="twitter:label2"]|content = $seo['twitter']['label2']
meta[name="twitter:data2"]|content = $seo['twitter']['data2']


script [type="application/ld+json] = $seo['json-schema-graph']