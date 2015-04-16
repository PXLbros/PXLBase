css_dir = "public/css"
javascripts_dir = "public/js"
images_dir = "public/images"
cache_path = "resources/assets/sass/.sass-cache"

output_style = (environment == :production) ? :compressed : :expanded
line_comments = (environment == :production) ? false : true
relative_assets = true

asset_cache_buster :none