param(
    [string]$BaseUrl = "http://localhost:8080"
)

$ErrorActionPreference = "Stop"
$BaseUrl = $BaseUrl.TrimEnd("/")

$routes = @(
    "/",
    "/home-systems/",
    "/marine-doors/",
    "/about-us/",
    "/contact/",
    "/news/",
    "/news/page/2/",
    "/category/products/",
    "/?s=marine",
    "/new-generation-sliding-door-systems/"
)

foreach ($route in $routes) {
    $response = Invoke-WebRequest -Uri "$BaseUrl$route" -UseBasicParsing
    if ($response.StatusCode -ne 200) {
        throw "Expected HTTP 200 for $route, received $($response.StatusCode)."
    }
    Write-Host "[OK] $route"
}

$notFound = curl.exe `
    --silent `
    --show-error `
    --output NUL `
    --write-out "%{http_code}" `
    "$BaseUrl/route-that-must-not-exist/"

if ([int]$notFound -ne 404) {
    throw "Expected a real 404 response, received $notFound."
}
Write-Host "[OK] custom 404 response"

$pages = Invoke-RestMethod -Uri "$BaseUrl/wp-json/wp/v2/pages?per_page=100&_fields=slug,status"
$requiredPages = @(
    "home",
    "home-systems",
    "marine-doors",
    "about-us",
    "contact",
    "news"
)

foreach ($slug in $requiredPages) {
    $page = $pages | Where-Object { $_.slug -eq $slug -and $_.status -eq "publish" }
    if (-not $page) {
        throw "Published Gutenberg page '$slug' was not found."
    }
    Write-Host "[OK] Gutenberg page: $slug"
}

$postsResponse = Invoke-WebRequest -Uri "$BaseUrl/wp-json/wp/v2/posts?per_page=1&_fields=id" -UseBasicParsing
$postTotal = [int]$postsResponse.Headers["X-WP-Total"]
if ($postTotal -lt 8) {
    throw "Expected at least 8 news posts, received $postTotal."
}
Write-Host "[OK] dynamic news posts: $postTotal"

$posts = Invoke-RestMethod -Uri "$BaseUrl/wp-json/wp/v2/posts?per_page=100&_fields=id,author"
$postsWithoutAuthor = $posts | Where-Object { $_.author -le 0 }
if ($postsWithoutAuthor) {
    throw "Every published news post must have a WordPress author."
}
Write-Host "[OK] news post authors"

$contact = Invoke-WebRequest -Uri "$BaseUrl/contact/" -UseBasicParsing
if (
    $contact.Content -notmatch 'name="aluteco_contact_nonce"' -or
    $contact.Content -notmatch 'name="company"'
) {
    throw "Contact form security fields are missing."
}
Write-Host "[OK] contact form security fields"

Write-Host ""
Write-Host "All ALUTECO HTTP smoke checks passed."
