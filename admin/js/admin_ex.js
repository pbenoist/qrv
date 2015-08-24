function bigImg(x) {
    var s = x.src;
    var indice = s.substr(s.length-5, 1);

    if (indice != "0")
    {
        x.style.height = "64px";
        x.style.width  = "48px";
    }
}

function normalImg(x) {
    x.style.height = "32px";
    x.style.width  = "24px";
}

