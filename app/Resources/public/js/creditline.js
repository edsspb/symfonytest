$ = jQuery;
$.fn.creditline = function(options) {

    var settings = $.extend( {
        'cl_basket_method' : 'post',
        'cl_basket_url'    : '',
        'cl_basket_param' : '',
        'cl_basket_func' : undefined,
        'caption' : '',
        'basket_link' : '',
        'type' : 'goods',
        'cl_send_type' : 'send_soap',
        'goods' : '',
        'CLOrderId' : '',
        'state' : null,
        'cl_autoStart' : false,
        'cl_no_click' : false,
        'url_success' : undefined,
        'sum' : 0,
        'pv' : '0%',
        'period' : 4,
        'fio' : '',
        'tel' : '',
        'mail' : '',
        'is18' : false,
        'isYes' : false,
        'showHeader' : false,
        'showButtons' : false
    }, options);

    return this.each(function() {
        if (settings['type'] == 'calc') {
            $(this).append('\
					<div id="CLcalc">\
                        ' + (options.showHeader ? '\
						    <h2 class="credit-title title green">Купить в кредит</h2>\
                            <p class="cl_descr">\
                                &#x2010; Минимальный возраст заёмщика - 18 лет<br />\
                                &#x2010; Сумма кредита - от 3 000 рублей до 500 000 рублей<br />\
                                &#x2010; Кредит предоставляется на срок  от 3 месяцев до 3 лет<br />\
                                &#x2010; Подписание кредитного договора в удобном для Вас месте и в удобное время<br />\
                                &#x2010; Для оформления нужен только паспорт гражданина РФ<br />\
                                &#x2010; Досрочное погашение кредита без комиссии\
                            </p>' : '') + '\
                        <div class="cl_label">Я хочу взять в кредит <span class="cl_credit_sum" id="cl_sum"></span></div>\
						<div class="cl_ldelimiter"></div>\
						<div class="cl_label">на срок в</div>\
						<div id="cl_sl3">\
							<div class="values"></div>\
							<span class="tooltip3"></span>\
							<div id="slider3"></div>\
							<div class="delimiters"></div>\
						</div>\
						<div class="cl_label">с первоначальным взносом</div>\
						<div id="cl_sl4">\
							<div class="values2"></div>\
							<span class="tooltip4"></span>\
							<div id="slider4"></div>\
							<div class="delimiters2"></div>\
						</div>\
						<div class="cl_label mnt">ежемесячный платеж: <span class="cl_credit_sum mnt"></span></div>\
                        ' + (options.showButtons ? '\
                            <div class="control-group">\
                                <a class="modal-credit-btn btn btn-large modal-close-btn not-add-btn" title="Продолжить покупки" href="#">Продолжить покупки</a>\
                                <a class="modal-credit-btn btn btn-large btn-warning goto-url not-add-btn" title="Перейти в корзину" data-url="'+options.basket_link+'" href="/basket/">Перейти в корзину</a>\
                            </div>' : '' ) + '\
						<div class="info-msg" style="text-align: center; font-family: \'HelveticaNeueCyr\', \'Myriad Pro\'; color: #969696; font-size: 14px;">Не является публичной офертой</div>\
						<div class="cl_logopartners big">\
							<a href="http://www.rsb.ru" target="_blank"><div class="logo_partner_1"></div></a>\
							<a href="http://www.homecredit.ru" target="_blank"><div class="logo_partner_4"></div></a>\
							<a href="http://www.rencredit.ru/" target="_blank"><div class="logo_partner_2"></div></a>\
						</div>\
						<div style="clear:both;"></div>\
						<input type="hidden" id="CLPeriod" name="CLPeriod" />\
						<input type="hidden" id="CLInitialPayment" name="CLInitialPayment" />\
						<input type="hidden" id="CLSum" name="CLSum" />\
						<input type="hidden" id="CLPayment" name="CLPayment" />\
					</div>\
				');

            $('#CLcalc').find('.btn.goto-url').click(function(e){
                e.preventDefault();
                var href = $(this).attr('href') + '?period=' + $('#CLPeriod').val() + '&vznos=' + parseInt(array_vznos[slider4.slider( "option", "value" )],10);
                window.location.href = href;
                return false;
            });

            cl_logo_partner();

            var SumCredit = cl_sum_removal_excess(settings['sum']);
            if (settings['sum_percent']) {
                SumPercent = cl_sum_percent_removal_excess(settings['sum_percent']);
                SumCredit = +SumCredit + SumCredit * SumPercent;
            } else if (ConfigPercent) SumCredit = +SumCredit + SumCredit * ConfigPercent;

            $("#CLcalc #cl_sum").html(number_format(SumCredit,0,'.',' ') + " руб.");

            var tooltip3 = $('.tooltip3');
            tooltip3.hide();
            slider3 = $( "#slider3" );
            slider3.slider({
                min: 0,
                max: array_month.length - 1,
                range: "min",
                start: function(event,ui) {
                    tooltip3.fadeIn('fast');
                },
                slide: function(event, ui) {
                    // var vl = $(".ui-slider-handle").position().left;
                },
                stop: function(event,ui) {
                    tooltip3.fadeOut('slow');
                }
            });

            $("#cl_sl3").mousemove(function(event) {
                var vl = $("#cl_sl3 .ui-slider-handle").position().left;
                var value = slider3.slider( "option", "value" );
                tooltip3.text(array_month[value] + " месяцев");
                tooltip3.css('left', vl - 54);
                CLCalculator3(array_month[value],array_vznos[slider4.slider( "option", "value" )]);
            });

            w = 444;
            s = array_month.length - 1;

            pos = slider3.position().left;

            for (i=0; i<s; i++) {
                vl = array_month[i];
                dop = (vl.length-1) * 5 + 15;
                $(".values").append( $( "<div class='val_slider'></div>" ).text(vl + " мес").css("left", pos-dop) );
                if (i > 0) $(".delimiters").append( $( "<div class='val_slider_grm'></div>" ).css("left", pos) );
                if (i > 0) $(".delimiters").append( $( "<div class='val_slider_grm_min'></div>" ).css("left", pos - w/s/2) );
                pos += w/s;
            }

            vl = array_month[i];
            $(".values").append( $( "<div class='val_slider'></div>" ).text(vl + " мес").css("left", w-w/s/15-15) );
            $(".delimiters").append( $( "<div class='val_slider_grm_min'></div>" ).css("left", pos - w/s/2) );

            var tooltip4 = $('.tooltip4');
            tooltip4.hide();
            slider4 = $('#slider4');


            slider4.slider({
                min: 0,
                max: array_vznos.length - 1,
                range: "min",
                start: function(event,ui) {
                    tooltip4.fadeIn('fast');
                },
                slide: function(event, ui) {
                    var vl = $(".ui-slider-handle").position().left;
                },
                stop: function(event,ui) {
                    tooltip4.fadeOut('slow');
                }
            });

            $("#cl_sl4").mousemove(function(event) {
                var vl = $("#cl_sl4 .ui-slider-handle").position().left;
                var value = slider4.slider( "option", "value" );
                tooltip4.text(array_vznos[value]);
                tooltip4.css('left', vl - 54);
                CLCalculator3(array_month[slider3.slider( "option", "value" )],array_vznos[value]);
            });

            w = 444;
            s = array_vznos.length - 1;

            pos = slider4.position().left;

            for (i=0; i<s; i++) {
                vl = array_vznos[i];
                dop = (vl.length-1) * 5;
                $(".values2").append( $( "<div class='val_slider'></div>" ).text(vl).css("left", pos-dop) );
                if (i > 0) $(".delimiters2").append( $( "<div class='val_slider_grm'></div>" ).css("left", pos) );
                if (i > 0) $(".delimiters2").append( $( "<div class='val_slider_grm_min'></div>" ).css("left", pos - w/s/2) );
                pos += w/s;
            }

            vl = array_vznos[i];
            $(".values2").append( $( "<div class='val_slider'></div>" ).text(vl).css("left", w-w/s/15) );
            $(".delimiters2").append( $( "<div class='val_slider_grm_min'></div>" ).css("left", pos - w/s/2) );

            slider3.slider("value", getSliderPos(settings['period']));
            slider4.slider("value", getSlider2Pos(settings['pv'].replace('%','')/100));

            CLCalculator3(array_month[slider3.slider( "option", "value" )],array_vznos[slider4.slider( "option", "value" )]);
        }
        $(this).click(onClick);

        function onClick(e) {
            if (settings['type'] == 'goods') {
                doc_height = $(document).height();
                $('.reveal-modal .cl_goods').html(settings['caption']);
                var SumCredit = cl_sum_removal_excess(settings['sum']);
                if (settings['sum_percent']) {
                    SumPercent = cl_sum_percent_removal_excess(settings['sum_percent']);
                    SumCredit = +SumCredit + SumCredit * SumPercent;
                } else if (ConfigPercent) SumCredit = +SumCredit + SumCredit * ConfigPercent;
                $('.reveal-modal .cl_price').html(number_format(SumCredit,0,'.',' ') + " руб.");
                $('.reveal-modal #cl_gotoBasket').attr("href" , settings['basket_link']);
                for (var i = 0; i < array_month.length; i++) GoodsPeriod = i;
                for (var i = 0; i < array_vznos.length; i++) GoodsFee = i;
                $(".cl_sum").html(CLCalculator2(array_month[GoodsPeriod], array_vznos[GoodsFee]));
                $('#CLPreRequest').reveal($(this).data());
            }
        }

    });

};
var ConfigPercent = 0;
var ProgramData = new Array (
    {'4':{'0':'0.2601','0.1':'0.2612','0.2':'0.2626','0.3':'0.2643','0.4':'0.2667','0.5':'0.27'},
        '6':{'0':'0.1767','0.1':'0.1778','0.2':'0.1792','0.3':'0.181','0.4':'0.1834','0.5':'0.1867'},
        '10':{'0':'0.11','0.1':'0.1112','0.2':'0.1126','0.3':'0.1143','0.4':'0.1167','0.5':'0.1201'},
        '12':{'0':'0.0934','0.1':'0.0945','0.2':'0.0959','0.3':'0.0977','0.4':'0.1','0.5':'0.1034'},
        '18':{'0':'0.0656','0.1':'0.0667','0.2':'0.0681','0.3':'0.0699','0.4':'0.0723','0.5':'0.0756'},
        '24':{'0':'0.0517','0.1':'0.0528','0.2':'0.0542','0.3':'0.056','0.4':'0.0584','0.5':'0.0617'},
        '30':{'0':'0.0434','0.1':'0.0445','0.2':'0.0459','0.3':'0.0477','0.4':'0.05','0.5':'0.0534'},
        '36':{'0':'0.0378','0.1':'0.0389','0.2':'0.0403','0.3':'0.0421','0.4':'0.0445','0.5':'0.0478'}}
);

var array_month = new Array("4", "6", "10", "12", "18", "24", "30", "36");
var array_vznos = new Array("0%", "10%", "20%", "30%", "40%", "50%");


var slider, slider3, slider4;

function getSliderPos(v) {
    i = 0;
    for (var key in ProgramData[0]) {
        if (v == key) return i;
        i++;
    }
    return i;
}

function getSlider2Pos(v) {
    i = 0;
    for (var key in ProgramData[0][4]) {
        if (v == key) return i;
        i++;
    }
    return i;
}

function CLCalculator2(Period,Fee) {
    var Pricesum = $(".reveal-modal .cl_price").html().replace(/руб./g, "").replace(/ /g, "").replace(/р./g, "");
    Fee = Fee.replace(/%/, "") / 100;
    var EP = (Pricesum-(Pricesum*Fee))*ProgramData[0][Period][Fee];
    EP = Math.round(EP).toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
    return EP + " руб.";
}

function CLCalculator3(Period,Fee) {
    var Pricesum = $("#CLcalc .cl_credit_sum").html().replace(/ руб./, "").replace(/ /, "").replace(/ р./, "");
    $("#CLcalc #CLSum").val(Pricesum);
    Fee = Fee.replace(/%/, "") / 100;
    var EP = (Pricesum-(Pricesum*Fee))*ProgramData[0][Period][Fee];
    $("#CLcalc #CLPayment").val(Math.round(EP));
    EP = Math.round(EP).toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
    $("#CLcalc .cl_credit_sum.mnt").html(EP + " руб.");
    $("#CLcalc #CLInitialPayment").val(Pricesum*Fee);
    $("#CLcalc #CLPeriod").val(Period);
}

$(function() {
    $('body').append('\
			<div id="CLPreRequest" class="reveal-modal prw">\
				<div class="cl_logo"><a href="http://www.l-kredit.ru" target="_blank"><img src="/images/cl_logo.jpg" /></a></div>\
				<div class="cl_basket">\
					<div class="cl_goods"></div>\
					<div class="cl_price"></div>\
					<div class="cl_inf">добавлен в корзину</div>\
				</div>\
				<div class="cl_credit">В кредит от <span class="cl_sum"></span> в месяц</div>\
				<p class="cl_descr">\
					&#x2010; Минимальный возраст заёмщика - 18 лет<br />\
					&#x2010; Сумма кредита - от 3 000 рублей до 500 000 рублей<br />\
					&#x2010; Кредит предоставляется на срок  от 3 месяцев до 3 лет<br />\
					&#x2010; Подписание кредитного договора в удобное для Вас  место и время<br />\
					&#x2010; Для оформления нужен только паспорт гражданина РФ<br />\
					&#x2010; Досрочное погашение кредита без комиссии\
				</p>\
				<div class="cl_buttons">\
					<div class="cl_pre_button"><a href="#" id="cl_gotoBasket"><div class="cl_btn_basket"></div></a></div>\
					<div class="cl_pre_button"><a href="#" id="cl_gotoContinue"><div class="cl_btn_continue"></div></a></div>\
				</div>\
				<div class="cl_logopartners">\
					<a href="http://www.rsb.ru" target="_blank"><div class="logo_partner_1"></div></a>\
					<a href="http://www.homecredit.ru" target="_blank"><div class="logo_partner_4"></div></a>\
					<a href="http://www.rencredit.ru/" target="_blank"><div class="logo_partner_2"></div></a>\
				</div>\
				<a class="close-reveal-modal"></a>\
			</div>\
		');

    cl_logo_partner();
});

function cl_logo_partner() {
    $(".logo_partner_1").hover(
        function(){
            $(this).css("background-position", "-151px 0");
        },
        function(){
            $(this).css("background-position", "0px 0px");
        }
    );
    $(".logo_partner_2").hover(
        function(){
            $(this).css("background-position", "-151px -70px");
        },
        function(){
            $(this).css("background-position", "0px -70px");
        }
    );
    $(".logo_partner_4").hover(
        function(){
            $(this).css("background-position", "-151px -105px");
        },
        function(){
            $(this).css("background-position", "0px -105px");
        }
    );
}

function cl_sum_removal_excess(value) {
    if(isNaN(value)){
        return value.replace(/руб./g, "").replace(/ /g, "").replace(/р./g, "");
    }else {
        return value;
    }
}

function cl_sum_percent_removal_excess(value) {

    value = value.replace(/%/g, "").replace(/ /g, "");
    value = value * 0.01;
    if (value > 0 && value < 1) return value;
    else return 0;

}

function number_format(number, decimals, dec_point, thousands_sep) {
    var i, j, kw, kd, km;
    if( isNaN(decimals = Math.abs(decimals)) ){
        decimals = 2;
    }
    if( dec_point == undefined ){
        dec_point = ",";
    }
    if( thousands_sep == undefined ){
        thousands_sep = ".";
    }
    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";
    if( (j = i.length) > 3 ){
        j = j % 3;
    } else{
        j = 0;
    }
    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");
    return km + kw + kd;
}