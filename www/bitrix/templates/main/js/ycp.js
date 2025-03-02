/******************************************************
* #### jQuery-Youtube-Channels-Playlist v7.0 ####
* Coded by Ican Bachors 2014.
* https://github.com/bachors/jQuery-Youtube-Channels-Playlist
* Updates will be posted to this site.
******************************************************/

$.fn.ycp = function(j) {
    const n = {
        playlist: 10,
        autoplay: false,
        related: false
    };
    j.playlist = (j.playlist == undefined ? n.playlist : j.playlist);
    j.autoplay = (j.autoplay == undefined ? n.autoplay : j.autoplay);
    j.related = (j.related == undefined ? n.related : j.related);
    $(this).each(function(i, a) {
        const b = ($(this).attr('id') != null && $(this).attr('id') != undefined ? `#${$(this).attr('id')}` : `.${$(this).attr('class')}`);
        const title = ($(this).data('ycp_title') == undefined ? 'YCP.js' : $(this).data('ycp_title'));
        const channel = $(this).data('ycp_channel');
        const html = `<div class="ycp"><div class="belah ycp_vid_play" title="Play video"></div><div class="belah" id="ycp_youtube_channels${i}"></div></div>`;
        $(this).html(html);
        if (channel.substring(0, 2) == 'PL' || channel.substring(0, 2) == 'UU') {
            const c = '';
            ycp_list(title, channel, c, i, b)
        } else {
            const d = (channel.substring(0, 2) == 'UC' ? 'id' : 'forUsername');
            ycp_play(title, channel, d, i, b)
        }
    });

    function ycp_play(g, c, d, e, f) {
      //console.log(`/include/ycp.php?${d}=${c}&key=${j.apikey}`);
        $.ajax({
            url: `/include/ycp.php?`,
            crossDomain: true,
            dataType: 'json'
        }).done(a => {
            //console.log(a);
            const b = a.items[0].contentDetails.relatedPlaylists.uploads;
            const pageToken = '';
            ycp_list(g, b, pageToken, e, f)
        }).fail(a => { //console.log(a)
          })
    }

    function ycp_list(h, f, g, k, l) {
        //console.log(`/include/ycpList.php?part=status,snippet&maxResults=${j.playlist}&playlistId=${f}&key=${j.apikey}&pageToken=${g}`);
        $.ajax({
            url: `/include/ycpList.php?part=status,snippet&maxResults=${j.playlist}&playlistId=${f}&key=${j.apikey}&pageToken=${g}`,
            dataType: 'json'
        }).done(c => {
          console.log(c);
            let d = '';
            d += '<div class="luhur">';
            d += `<div class="title">${h}</div>`;
            //d += '<span class="tombol vid-prev" title="Previous videos">Пред</span> ';
            //d += '<span class="tombol vid-next" title="Next videos">След</span>';
	    d += '</div><div class="handap">';
            $.each(c.items, (i, a) => {
                if (c.items[i].status.privacyStatus == "public") {
                    const b = c.items[i].snippet.resourceId.videoId;
                    ycp_part(b, i, k, l);
                    d += `<div class="play" data-vvv="${b}" data-img="${c.items[i].snippet.thumbnails.high.url}" title="${c.items[i].snippet.title}"><div class="thumb"><img src="${c.items[i].snippet.thumbnails.default.url}" alt=" "><span class="tm${i}"></span></div>`;
                    d += `<div class="title">${c.items[i].snippet.title}</div><span class="mute by${i}"></span><br><span class="mute views${i}"></span> <span class="mute">-</span> <span class="mute date${i}"></span></div>`
                }
            });
            d += '</div>';
            $(`${l} .ycp div#ycp_youtube_channels${k}`).html(d);
            if (c.prevPageToken == null || c.prevPageToken == undefined) {
                const e = $(`${l} .ycp div#ycp_youtube_channels${k} div.play`).attr("data-vvv");
                const imag = $(`${l} .ycp div#ycp_youtube_channels${k} div.play`).attr("data-img");
                if (j.autoplay == false) {
                    $(`${l} .ycp div.ycp_vid_play:eq(${k})`).html('<a href="#"></a>');
                    $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('background', `url(${imag}) no-repeat`);
                    $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('-webkit-background-size', 'cover');
                    $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('-moz-background-size', 'cover');
                    $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('-o-background-size', 'cover');
                    $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('background-size', 'cover')
                } else {
                    $(`${l} .ycp div.ycp_vid_play:eq(${k})`).html(`<iframe src="//www.youtube.com/embed/${e}?rel=${j.related ? 1 : 0}&amp;autoplay=1&amp;hl=`+$('.fb-youtube-block #unix').attr('data-lang')+`" allowfullscreen="" frameborder="0" class="bingkay"></iframe>`)
                }
                $(`${l} .ycp div#ycp_youtube_channels${k} div`).removeClass('vid-active');
                $(`${l} .ycp div#ycp_youtube_channels${k} div.play:eq(0)`).addClass('vid-active')
            } else {
                $(`${l} .ycp div#ycp_youtube_channels${k} span.vid-prev`).click(() => {
                    g = c.prevPageToken;
                    ycp_list(h, f, g, k, l);
                    return false
                })
            }
            $(`${l} .ycp div#ycp_youtube_channels${k} span.vid-next`).click(() => {
                g = c.nextPageToken;
                ycp_list(h, f, g, k, l);
                return false
            });
            $(`${l} .ycp div#ycp_youtube_channels${k} div.play`).each(function() {
                $(this).click(function() {
                    const a = $(this).attr("data-vvv");
                    const m = $(this).attr("data-img");
                    $(`${l} .ycp div#ycp_youtube_channels${k} div`).removeClass('vid-active');
                    $(this).addClass('vid-active');
                    if (j.autoplay == false) {
                        $(`${l} .ycp div.ycp_vid_play:eq(${k})`).html('<a href="#"></a>');
                        $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('background', `url(${m}) no-repeat`);
                        $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('-webkit-background-size', 'cover');
                        $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('-moz-background-size', 'cover');
                        $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('-o-background-size', 'cover');
                        $(`${l} .ycp div.ycp_vid_play:eq(${k})`).css('background-size', 'cover')
                    } else {
                        $(`${l} .ycp div.ycp_vid_play:eq(${k})`).html(`<iframe src="//www.youtube.com/embed/${a}?rel=${j.related ? 1 : 0}&amp;autoplay=1" allowfullscreen="" frameborder="0" class="bingkay"></iframe>`)
                    }
                    return false
                })
            });
            $(`${l} .ycp div.ycp_vid_play:eq(${k})`).click(function() {
                const a = $(`${l} .ycp div#ycp_youtube_channels${k} div.play.vid-active`).attr("data-vvv");
                $(this).html(`<iframe src="//www.youtube.com/embed/${a}?rel=${j.related ? 1 : 0}&amp;autoplay=1" allowfullscreen="" frameborder="0" class="bingkay"></iframe>`);
                return false
            })
        }).fail(c => { //console.log(c)
          })
    }

    function ycp_part(c, i, d, e) {
        $.ajax({
            url: `/include/ycpVideo.php?id=${c}&key=${j.apikey}&part=contentDetails,snippet,statistics`,
            dataType: 'json'
        }).done(a => {
          //console.log("ASK VIDEO");
            const b = a.items[0].contentDetails.duration;
            let dataw = '';
            let menit = '';
            let detik = '';
            if (b.match(/M/g)) {
                dataw = b.split('M');
                menit = dataw[0].replace('PT', '');
                if (dataw[1] != '') {
                    detik = dataw[1].replace('S', '')
                } else {
                    detik = '00'
                }
            } else {
                dataw = b.split('PT');
                menit = '00';
                detik = dataw[1].replace('S', '')
            }
            detik = (detik.length > 1 ? detik : `0${detik}`);
            $(`${e} .ycp div#ycp_youtube_channels${d} span.tm${i}`).html(`${menit}:${detik}`);
            $(`${e} .ycp div#ycp_youtube_channels${d} span.by${i}`).html(`${a.items[0].snippet.channelTitle}`);
            $(`${e} .ycp div#ycp_youtube_channels${d} span.views${i}`).html(`${addCommas(a.items[0].statistics.viewCount)}`);
            $(`${e} .ycp div#ycp_youtube_channels${d} span.date${i}`).html(_timeSince(new Date(a.items[0].snippet.publishedAt).getTime()))
        })
    }

    function _timeSince(a) {
      const s = Math.floor((new Date() - a) / 1000);
      let i = Math.floor(s / 31536000);
      if($("#unix").attr("data-lang") == "ru"){
        if (i > 1) {
            return `${decl(i,["год", "года", "лет"])} назад`
        }
        i = Math.floor(s / 2592000);
        if (i > 1) {
            return `${decl(i,["месяц", "месяца", "месяцев"])} назад`
        }
        i = Math.floor(s / 86400);
        if (i > 1) {
            return `${decl(i,["день", "дня", "дней"])} назад`
        }
        i = Math.floor(s / 3600);
        if (i > 1) {
            return `${decl(i,["час", "часа", "часов"])} назад`
        }
        i = Math.floor(s / 60);
        if (i > 1) {
            return `${decl(i,["минуту", "минуты", "минут"])} назад`
        }
        s = Math.floor(s);
        return `{decl(s,["секунду", "секунды", "секунд"])}seconds назад`
      }else{
        
        if (i > 1) {
            return `${i} years ago`
        }
        i = Math.floor(s / 2592000);
        if (i > 1) {
            return `${i} months ago`
        }
        i = Math.floor(s / 86400);
        if (i > 1) {
            return `${i} days ago`
        }
        i = Math.floor(s / 3600);
        if (i > 1) {
            return `${i} hours ago`
        }
        i = Math.floor(s / 60);
        if (i > 1) {
            return `${i} minutes ago`
        }
        return `${Math.floor(s)} seconds ago`
      }
    }

    function addCommas(a) {
        a += '';
        x = a.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? `.${x[1]}` : '';
        const b = /(\d+)(\d{3})/;
        while (b.test(x1)) {
            x1 = x1.replace(b, '$1' + ',' + '$2')
        }
        x = x1 + x2;
        if($("#unix").attr("data-lang") == "ru")
          return decl(x, ["просмотр","просмотра","просмотров"]);
        else
          return x + " views";
    }
    
    function decl(n, forms){
      modulo = n % 10;
      dec = n % 100;
      str = '';
      if((dec > 9 && dec < 20) || modulo > 4 || modulo == 0){
        str = forms[2];
      }else if(modulo == 1){
        str = forms[0];
      }else{
        str = forms[1];
      }
      return n + ' ' + str;
    }
}


$(document).ready(function(){ 
  (function () {
        let isIE11 = !!window.MSInputMethodContext && !!document.documentMode;

        if(isIE11) {
            if (document.querySelector('.video')){
                document.querySelector('.video').style.display = 'flex';

            } 
        } else {
                $("#unix").ycp({
                    apikey : 'AIzaSyBsd-y2rCNoNSidaT1Dui3beINjnEx4OdU',
                    playlist : 50,
                    autoplay : true,
                    related : true
                });
            }
    }());
});
