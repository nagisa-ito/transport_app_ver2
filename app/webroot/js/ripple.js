//画面サイズの取得
function getWindowSize() {
    var circlesize = Math.max(window.parent.screen.width, window.parent.screen.height);
};

//click イベントで発火
var target = document.getElementById('login_button');
target.addEventListener("click", drop, false);

function drop(e) {

    //座標の取得
    var x = e.pageX;
    var y = e.pageY;

    //しずくになるdivの生成、座標の設定
    var sizuku = document.createElement("div");
    sizuku.style.top = y + "px";
    sizuku.style.left = x + "px";
    document.body.appendChild(sizuku);

    //アニメーションをする className を付ける
    sizuku.className = "sizuku";

    //アニメーションが終わった事を感知してしずくを remove する
    sizuku.addEventListener("animationend", function() {
        this.parentNode.removeChild(this);
    }, false);
}
