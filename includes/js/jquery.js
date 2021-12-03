$(function(){
    $('.menu-trigger').click(function(){
        if($(this).hasClass('active')){
            $(this).removeClass('active');
            $('main').removeClass('open');
            $('nav').removeClass('open');
            $('.overlay').removeClass('open');
        } else {
            $(this).addClass('active');
            $('main').addClass('open');
            $('nav').addClass('open');
            $('.overlay').addClass('open');
        }
    }); //クリック時のモーション（アクション）
    $('.overlay').click(function(){
        if($(this).hasClass('open')){
            $(this),removeClass('open');
            $('.menu-trigger').removeClass('active');
            $('main').removeClass('open');
            $('nav').removeClass('open');
        }
    });
});

// --------------------------------------------------- profile_editのプロフィール画像用
$(function(){
    $('.img_upload').change(function (e) {
        let fileset = $(this).val();
        let place = $(this).next("img .img_preview");

        if (fileset === '') {
            place.attr('src', "");
        } else {
            let reader = new FileReader();
            reader.onload = function (e) {
                place.attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
            $('[name="delete"]').css({'display':''});
            $('[name="img_delete"]').prop("checked", false);
        }
    });

    $('#profile_img').change(function (e) {
        let fileset = $(this).val();
        if (fileset === '') {
            $("#tl_img").attr('src', "");
            $('[name="delete"]').css({'display':'none'});
        } else {
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#tl_img").attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    $('[name="img_delete"]').change(function () {
        $("#tl_img").attr('src', "");
        $('[name="delete"]').css({'display':'none'});
    });
});
// ---------------------------------------------------

// --------------------------------------------------- プログラミング言語入力
// プログラミング言語の入力フォームの最小値と最大値
let minCount = 1;
let maxCount = 10;

$(function(){
    // プラスのボタンを押された時に動く
    $('#lans-plus').click(function(){

        // id="demo-area"の入力フォームの数を取得
        let inputCount = $('#lans-area .unit').length;

        if (inputCount < maxCount){

            // class="unit"の最後の要素をコピーする
            let element = $('#lans-area .unit:last-child').clone(true);

            // 1つ目のinput(type="text")の要素を持ってくる
            let inputList = element[0].querySelectorAll('input[type="text"]');

            for (let i = 0; i < inputList.length; i++) {

                // 新しく追加するフォームのvalueを初期化
                inputList[i].value = "";

            }

            // id="demo-area"に入力フォームを1つ追加
            $('#lans-area .unit').parent().append(element);
        }
    });

    // マイナスのボタンを押された時に動く
    $('.lans-minus').click(function(){

        // id="demo-area"の入力フォームの数を取得
        let inputCount = $('#lans-area .unit').length;

        if (inputCount > minCount){

            // 選ばれたマイナスボタンの横のフォーム要素を削除する
            $(this).parents('.unit').remove();
        }
    });
});
// ----------------------------------------------


// ログアウト用
// ----------------------------------------------
$(function(){
    $("#logout").click(function(){
        var result= window.confirm('ログアウトします。よろしいですか？');
        if(result){
            window.location.href = "../logout.php";
        }
    })
});
// ----------------------------------------------

// urlコピー用
// ----------------------------------------------
function copyToClipboard() {
    // コピー対象のテキストを選択す
    let copy_target = document.getElementById('url').value;

    navigator.clipboard.writeText(copy_target);

    // コピーをお知らせする
    alert("コピーできました！ : " + copy_target);
}
// ----------------------------------------------


// お気に入り用
// ----------------------------------------------
$(function(){
    $("#favorite").change(function(){
        let s = $("#favorite").prop('checked');
        $.ajax({
            type: "POST",
            url: "./favorite.php",
            data: {
                "favorited_id" : favorited_id,
                "InDel" : s
            }
        })
    });
});
// ----------------------------------------------

// チャット用
// ----------------------------------------------
function send_message() {
    $.ajax({
        type: 'post',
        url: './chat_write.php',
        data: {
            'receiver_id': $('#receiver_id').val(),
            'chat_text': $('#chat_text').val()
        }
    })
    .then(
        function (data) {
            read_message();
            $('#chat_text').val('');
        },
        function () {
            alert("送信失敗");
        }
    );
}

function send_only_message() {
    $.ajax({
        type: 'post',
        url: '../chat/chat_write.php',
        data: {
            'receiver_id': $('#receiver_id').val(),
            'chat_text': $('#chat_text').val()
        }
    })
    .then(
        function (data) {
            alert($('#chat_text').val() + "の送信に成功しました");
            $('#chat_text').val('');
        },
        function () {
            alert("送信失敗");
        }
    );
}

function read_message() {
    $.ajax({
        type: 'post',
        url: './chat_output.php',
        data: {
            'receiver_id': $('#receiver_id').val()
        }
    })
    .then(
        function (data) {
            $('#messageTextBox').html(data);
        },
        function () {
            alert("読み込み失敗");
        }
    );
}
// ----------------------------------------------




        //変数定義
        var navigationOpenFlag = false;
        var navButtonFlag = true;
        var focusFlag = false;

        //ハンバーガーメニュー
            $(function(){

            $(document).on('click','.el_humburger',function(){
                if(navButtonFlag){
                spNavInOut.switch();
                //一時的にボタンを押せなくする
                setTimeout(function(){
                    navButtonFlag = true;
                },200);
                navButtonFlag = false;
                }
            });
            $(document).on('click touchend', function(event) {
                if (!$(event.target).closest('.navi,.el_humburger').length && $('body').hasClass('js_humburgerOpen') && focusFlag) {
                focusFlag = false;
                //scrollBlocker(false);
                spNavInOut.switch();
                }
            });
            });

        //ナビ開く処理
        function spNavIn(){
        $('body').removeClass('js_humburgerClose');
        $('body').addClass('js_humburgerOpen');
        setTimeout(function(){
            focusFlag = true;
        },200);
        setTimeout(function(){
            navigationOpenFlag = true;
        },200);
        }

        //ナビ閉じる処理
        function spNavOut(){
        $('body').removeClass('js_humburgerOpen');
        $('body').addClass('js_humburgerClose');
        setTimeout(function(){
            $(".uq_spNavi").removeClass("js_appear");
            focusFlag = false;
        },200);
        navigationOpenFlag = false;
        }

        //ナビ開閉コントロール
        var spNavInOut = {
        switch:function(){
            if($('body.spNavFreez').length){
            return false;
            }
            if($('body').hasClass('js_humburgerOpen')){
            spNavOut();
            } else {
            spNavIn();
            }
        }
    };
// navigation bar js





(function($) { "use strict";

	$(function() {
		var header = $(".start-style");
		$(window).scroll(function() {
			var scroll = $(window).scrollTop();

			if (scroll >= 10) {
				header.removeClass('start-style').addClass("scroll-on");
			} else {
				header.removeClass("scroll-on").addClass('start-style');
			}
		});
	});

	//Animation

	$(document).ready(function() {
		$('body.hero-anime').removeClass('hero-anime');
	});

	//Menu On Hover

	$('body').on('mouseenter mouseleave','.nav-item',function(e){
			if ($(window).width() > 750) {
				var _d=$(e.target).closest('.nav-item');_d.addClass('show');
				setTimeout(function(){
				_d[_d.is(':hover')?'addClass':'removeClass']('show');
				},1);
			}
	});

	//Switch light/dark

	$("#switch").on('click', function () {
		if ($("body").hasClass("dark")) {
			$("body").removeClass("dark");
			$("#switch").removeClass("switched");
		}
		else {
			$("body").addClass("dark");
			$("#switch").addClass("switched");
		}
	});

  })(jQuery);