# featured-shortcode
A new WordPress shortcode again

If your page has children it will return them:

`[featured-posts]`

You can add blog post IDs to show them:

`[featured-posts ids="2685,2465,2976,2731"]`

Or a category ID:

`[featured-posts category=6]`

If you want more/less columns then 4, add a number, max is 6:

`[featured-posts category=6 columns=6]`

I you want no excerpt, add 0:

`[featured-posts category=6 columns=6 maxtext=0]`

If you want more/less excerpt, add a number:

`[featured-posts category=6 columns=3 maxtext=50]`

If you want the text next to the image and not below, add direction:

`[featured-posts category=6 direction="h" columns=2 maxtext=100]`

You can find in the picture:

`[featured-posts category=6 columns=3 maxtext=50]`

`[featured-posts category=6 direction="h" columns=1]`

![featured-shortcode-result](https://github.com/LioneAdri/featured-shortcode/blob/master/image.jpg?raw=true)
