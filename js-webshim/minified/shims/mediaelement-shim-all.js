jQuery.webshims.ready("dom-support",function(e,f,o,s){var m=s.createElement("a");["poster","src"].forEach(function(k){f.defineNodeNamesProperty(k=="src"?["audio","video","source"]:["video"],k,{prop:{get:function(){var i=this.getAttribute(k);if(i==null)return"";m.setAttribute("href",i+"");return!e.support.hrefNormalized?m.getAttribute("href",4):m.href},set:function(i){e.attr(this,k,i)}}})});["autoplay","controls"].forEach(function(e){f.defineNodeNamesBooleanProperty(["audio","video"],e)});f.defineNodeNamesProperties(["audio",
"video"],{HAVE_CURRENT_DATA:{value:2},HAVE_ENOUGH_DATA:{value:4},HAVE_FUTURE_DATA:{value:3},HAVE_METADATA:{value:1},HAVE_NOTHING:{value:0},NETWORK_EMPTY:{value:0},NETWORK_IDLE:{value:1},NETWORK_LOADING:{value:2},NETWORK_NO_SOURCE:{value:3}},"prop")});
jQuery.webshims.register("mediaelement-swf",function(e,f,o,s,m,k){var i=f.mediaelement,E=o.swfobject,C=Modernizr.audio&&Modernizr.video,F=E.hasFlashPlayerVersion("9.0.115"),t=0,o={paused:!0,ended:!1,currentSrc:"",duration:o.NaN,readyState:0,networkState:0,videoHeight:0,videoWidth:0,error:null,buffered:{start:function(a){if(a)f.error("buffered index size error");else return 0},end:function(a){if(a)f.error("buffered index size error");else return 0},length:0}},g=Object.keys(o),A={currentTime:0,volume:1,
muted:!1};Object.keys(A);var y=e.extend({isActive:"html5",activating:"html5",wasSwfReady:!1,_bufferedEnd:0,_bufferedStart:0,_metadata:!1,_durationCalcs:-1,_callMeta:!1,currentTime:0,_ppFlag:m},o,A),w=/^jwplayer-/,r=function(a){if(a=s.getElementById(a.replace(w,"")))return a=f.data(a,"mediaelement"),a.isActive=="flash"?a:null},n=function(a){return(a=f.data(a,"mediaelement"))&&a.isActive=="flash"?a:null},h=function(a,b){b=e.Event(b);b.preventDefault();e.event.trigger(b,m,a)},z=k.playerPath||f.cfg.basePath+
"jwplayer/"+(k.playerName||"player.swf"),B=k.pluginPath||f.cfg.basePath+"swf/jwwebshims.swf";f.extendUNDEFProp(k.jwParams,{allowscriptaccess:"always",allowfullscreen:"true",wmode:"transparent"});f.extendUNDEFProp(k.jwVars,{screencolor:"ffffffff"});f.extendUNDEFProp(k.jwAttrs,{bgcolor:"#000000"});var v=function(a,b){var c=a.duration;if(!(c&&a._durationCalcs>0)){try{if(a.duration=a.jwapi.getPlaylist()[0].duration,!a.duration||a.duration<=0||a.duration===a._lastDuration)a.duration=c}catch(d){}a.duration&&
a.duration!=a._lastDuration?(h(a._elem,"durationchange"),(a._elemNodeName=="audio"||a._callMeta)&&i.jwEvents.Model.META(e.extend({duration:a.duration},b),a),a._durationCalcs--):a._durationCalcs++}},c=function(a,b){a<3&&clearTimeout(b._canplaythroughTimer);if(a>=3&&b.readyState<3)b.readyState=a,h(b._elem,"canplay"),clearTimeout(b._canplaythroughTimer),b._canplaythroughTimer=setTimeout(function(){c(4,b)},4E3);if(a>=4&&b.readyState<4)b.readyState=a,h(b._elem,"canplaythrough");b.readyState=a};i.jwEvents=
{View:{PLAY:function(a){var b=r(a.id);if(b&&!b.stopPlayPause&&(b._ppFlag=!0,b.paused==a.state)){b.paused=!a.state;if(b.ended)b.ended=!1;h(b._elem,a.state?"play":"pause")}}},Model:{BUFFER:function(a){var b=r(a.id);if(b&&"percentage"in a&&b._bufferedEnd!=a.percentage){b.networkState=a.percentage==100?1:2;(isNaN(b.duration)||a.percentage>5&&a.percentage<25||a.percentage===100)&&v(b,a);if(b.ended)b.ended=!1;if(b.duration){a.percentage>2&&a.percentage<20?c(3,b):a.percentage>20&&c(4,b);if(b._bufferedEnd&&
b._bufferedEnd>a.percentage)b._bufferedStart=b.currentTime||0;b._bufferedEnd=a.percentage;b.buffered.length=1;if(a.percentage==100)b.networkState=1,c(4,b);e.event.trigger("progress",m,b._elem,!0)}}},META:function(a,b){if(b=b&&b.networkState?b:r(a.id))if("duration"in a){if(!b._metadata||!((!a.height||b.videoHeight==a.height)&&a.duration===b.duration)){b._metadata=!0;var J=b.duration;if(a.duration)b.duration=a.duration;b._lastDuration=b.duration;if(a.height||a.width)b.videoHeight=a.height||0,b.videoWidth=
a.width||0;if(!b.networkState)b.networkState=2;b.readyState<1&&c(1,b);b.duration&&J!==b.duration&&h(b._elem,"durationchange");h(b._elem,"loadedmetadata")}}else b._callMeta=!0},TIME:function(a){var b=r(a.id);if(b&&b.currentTime!==a.position){b.currentTime=a.position;b.duration&&b.duration<b.currentTime&&v(b,a);b.readyState<2&&c(2,b);if(b.ended)b.ended=!1;h(b._elem,"timeupdate")}},STATE:function(a){var b=r(a.id);if(b)switch(a.newstate){case "BUFFERING":if(b.ended)b.ended=!1;c(1,b);h(b._elem,"waiting");
break;case "PLAYING":b.paused=!1;b._ppFlag=!0;b.duration||v(b,a);b.readyState<3&&c(3,b);if(b.ended)b.ended=!1;h(b._elem,"playing");break;case "PAUSED":if(!b.paused&&!b.stopPlayPause)b.paused=!0,b._ppFlag=!0,h(b._elem,"pause");break;case "COMPLETED":b.readyState<4&&c(4,b),b.ended=!0,h(b._elem,"ended")}}},Controller:{ERROR:function(a){var b=r(a.id);b&&i.setError(b._elem,a.message)},SEEK:function(a){var b=r(a.id);if(b){if(b.ended)b.ended=!1;if(b.paused)try{b.jwapi.sendEvent("play","false")}catch(c){}if(b.currentTime!=
a.position)b.currentTime=a.position,h(b._elem,"timeupdate")}},VOLUME:function(a){var b=r(a.id);if(b&&(a=a.percentage/100,b.volume!=a))b.volume=a,h(b._elem,"volumechange")},MUTE:function(a){if(!a.state){var b=r(a.id);if(b&&b.muted!=a.state)b.muted=a.state,h(b._elem,"volumechange")}}}};var d=function(a){e.each(i.jwEvents,function(b,c){e.each(c,function(c){a.jwapi["add"+b+"Listener"](c,"jQuery.webshims.mediaelement.jwEvents."+b+"."+c)})})},l=function(a){a&&(a._ppFlag===m&&e.prop(a._elem,"autoplay")||
!a.paused)&&setTimeout(function(){if(a.isActive=="flash"&&(a._ppFlag===m||!a.paused))try{e(a._elem).play()}catch(b){}},1)},j=function(a){if(a&&a._elemNodeName=="video"){var b,c,d,i,u,p,l,j,f=function(f,q){if(q&&f&&!(q<1||f<1||a.isActive!="flash"))if(b&&(b.remove(),b=!1),i=f,u=q,clearTimeout(l),c=a._elem.style.width=="auto",d=a._elem.style.height=="auto",c||d){p=p||e(a._elem).getShadowElement();var g;c&&!d?(g=p.height(),f*=g/q,q=g):!c&&d&&(g=p.width(),q*=g/f,f=g);j=!0;setTimeout(function(){j=!1},9);
p.css({width:f,height:q})}},q=function(){if(!(a.isActive!="flash"||e.prop(a._elem,"readyState")&&e.prop(this,"videoWidth"))){var p=e.prop(a._elem,"poster");if(p&&(c=a._elem.style.width=="auto",d=a._elem.style.height=="auto",c||d))b&&(b.remove(),b=!1),b=e('<img style="position: absolute; height: auto; width: auto; top: 0px; left: 0px; visibility: hidden;" />'),b.bind("load error alreadycomplete",function(){clearTimeout(l);var a=this,c=a.naturalWidth||a.width||a.offsetWidth,d=a.naturalHeight||a.height||
a.offsetHeight;d&&c?(f(c,d),a=null):setTimeout(function(){c=a.naturalWidth||a.width||a.offsetWidth;d=a.naturalHeight||a.height||a.offsetHeight;f(c,d);b&&(b.remove(),b=!1);a=null},9);e(this).unbind()}).prop("src",p).appendTo("body").each(function(){this.complete||this.error?e(this).triggerHandler("alreadycomplete"):(clearTimeout(l),l=setTimeout(function(){e(a._elem).triggerHandler("error")},9999))})}};e(a._elem).bind("loadedmetadata",function(){f(e.prop(this,"videoWidth"),e.prop(this,"videoHeight"))}).bind("emptied",
q).bind("swfstageresize",function(){j||f(i,u)}).bind("emptied",function(){i=void 0;u=void 0}).triggerHandler("swfstageresize");q();e.prop(a._elem,"readyState")&&f(e.prop(a._elem,"videoWidth"),e.prop(a._elem,"videoHeight"))}};i.playerResize=function(a){a&&(a=s.getElementById(a.replace(w,"")))&&e(a).triggerHandler("swfstageresize")};e(s).bind("emptied",function(a){a=n(a.target);l(a)});var q;i.jwPlayerReady=function(a){var b=r(a.id);if(b&&b.jwapi){clearTimeout(q);b.jwData=a;b.shadowElem.removeClass("flashblocker-assumed");
b.wasSwfReady?e(b._elem).mediaLoad():(a=parseFloat(a.version,10),(a<5.6||a>=6)&&f.warn("mediaelement-swf is only testet with jwplayer 5.6+"),e.prop(b._elem,"volume",b.volume),e.prop(b._elem,"muted",b.muted),d(b));b.wasSwfReady=!0;var a=b.actionQueue.length,c=0,i;if(a&&b.isActive=="flash")for(;b.actionQueue.length&&a>c;)c++,i=b.actionQueue.shift(),b.jwapi[i.fn].apply(b.jwapi,i.args);if(b.actionQueue.length)b.actionQueue=[];l(b)}};var D=e.noop;if(C){var K={play:1,playing:1},G="play,pause,playing,canplay,progress,waiting,ended,loadedmetadata,durationchange,emptied".split(","),
H=G.map(function(a){return a+".webshimspolyfill"}).join(" "),L=function(a){var b=f.data(a.target,"mediaelement");b&&(a.originalEvent&&a.originalEvent.type===a.type)==(b.activating=="flash")&&(a.stopImmediatePropagation(),K[a.type]&&b.isActive!=b.activating&&e(a.target).pause())},D=function(a){e(a).unbind(H).bind(H,L);G.forEach(function(b){f.moveToFirstEvent(a,b)})};D(s)}i.setActive=function(a,b,c){c||(c=f.data(a,"mediaelement"));if(c&&c.isActive!=b){b!="html5"&&b!="flash"&&f.warn("wrong type for mediaelement activating: "+
b);var d=f.data(a,"shadowData");c.activating=b;e(a).pause();c.isActive=b;b=="flash"?(d.shadowElement=d.shadowFocusElement=c.shadowElem[0],e(a).hide().getShadowElement().show()):(e(a).show().getShadowElement().hide(),d.shadowElement=d.shadowFocusElement=!1)}};var M=function(){var a="_bufferedEnd,_bufferedStart,_metadata,_ppFlag,currentSrc,currentTime,duration,ended,networkState,paused,videoHeight,videoWidth,_callMeta,_durationCalcs".split(","),b=a.length;return function(d){if(d){var e=b,i=d.networkState;
for(c(0,d);--e;)delete d[a[e]];d.actionQueue=[];d.buffered.length=0;i&&h(d._elem,"emptied")}}}(),I=function(a,b){var c=a._elem,d=a.shadowElem;e(c)[b?"addClass":"removeClass"]("webshims-controls");a._elemNodeName=="audio"&&!b?d.css({width:0,height:0}):d.css({width:c.style.width||e(c).width(),height:c.style.height||e(c).height()})};i.createSWF=function(a,b,c){if(F){t<1?t=1:t++;var d=e.extend({},k.jwVars,{image:e.prop(a,"poster")||"",file:b.srcProp}),l=e(a).data("jwvars")||{};if(c&&c.swfCreated)i.setActive(a,
"flash",c),M(c),c.currentSrc=b.srcProp,e.extend(d,l),k.changeJW(d,a,b,c,"load"),x(a,"sendEvent",["LOAD",d]);else{var u=e.prop(a,"controls"),p="jwplayer-"+f.getID(a),g=e.extend({},k.jwParams,e(a).data("jwparams")),h=a.nodeName.toLowerCase(),n=e.extend({},k.jwAttrs,{name:p,id:p},e(a).data("jwattrs")),m=e('<div class="polyfill-'+h+' polyfill-mediaelement" id="wrapper-'+p+'"><div id="'+p+'"></div>').css({position:"relative",overflow:"hidden"}),c=f.data(a,"mediaelement",f.objectCreate(y,{actionQueue:{value:[]},
shadowElem:{value:m},_elemNodeName:{value:h},_elem:{value:a},currentSrc:{value:b.srcProp},swfCreated:{value:!0},buffered:{value:{start:function(a){if(a>=c.buffered.length)f.error("buffered index size error");else return 0},end:function(a){if(a>=c.buffered.length)f.error("buffered index size error");else return(c.duration-c._bufferedStart)*c._bufferedEnd/100+c._bufferedStart},length:0}}}));I(c,u);m.insertBefore(a);C&&e.extend(c,{volume:e.prop(a,"volume"),muted:e.prop(a,"muted")});e.extend(d,{id:p,
controlbar:u?k.jwVars.controlbar||(h=="video"?"over":"bottom"):h=="video"?"none":"bottom",icons:""+(u&&h=="video")},l,{playerready:"jQuery.webshims.mediaelement.jwPlayerReady"});d.plugins?d.plugins+=","+B:d.plugins=B;f.addShadowDom(a,m);D(a);i.setActive(a,"flash",c);k.changeJW(d,a,b,c,"embed");j(c);E.embedSWF(z,p,"100%","100%","9.0.0",!1,d,g,n,function(b){if(b.success)c.jwapi=b.ref,u||e(b.ref).attr("tabindex","-1").css("outline","none"),setTimeout(function(){if(!b.ref.parentNode&&m[0].parentNode||
b.ref.style.display=="none")m.addClass("flashblocker-assumed"),e(a).trigger("flashblocker"),f.warn("flashblocker assumed");e(b.ref).css({minHeight:"2px",minWidth:"2px",display:"block"})},9),q||(clearTimeout(q),q=setTimeout(function(){var a=e(b.ref);a[0].offsetWidth>1&&a[0].offsetHeight>1&&location.protocol.indexOf("file:")===0?f.warn("Add your local development-directory to the local-trusted security sandbox:  http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager04.html"):
(a[0].offsetWidth<2||a[0].offsetHeight<2)&&f.info("JS-SWF connection can't be established on hidden or unconnected flash objects")},8E3))})}}else setTimeout(function(){e(a).mediaLoad()},1)};var x=function(a,b,c,d){return(d=d||n(a))?(d.jwapi&&d.jwapi[b]?d.jwapi[b].apply(d.jwapi,c||[]):(d.actionQueue.push({fn:b,args:c}),d.actionQueue.length>10&&setTimeout(function(){d.actionQueue.length>5&&d.actionQueue.shift()},99)),d):!1};["audio","video"].forEach(function(a){var b={},c,d=function(d){a=="audio"&&
(d=="videoHeight"||d=="videoWidth")||(b[d]={get:function(){var a=n(this);return a?a[d]:C&&c[d].prop._supget?c[d].prop._supget.apply(this):y[d]},writeable:!1})},i=function(a,c){d(a);delete b[a].writeable;b[a].set=c};i("volume",function(a){var b=n(this);if(b){if(a*=100,!isNaN(a)){var d=b.muted;(a<0||a>100)&&f.error("volume greater or less than allowed "+a/100);x(this,"sendEvent",["VOLUME",a],b);if(d)try{b.jwapi.sendEvent("mute","true")}catch(e){}a/=100;if(!(b.volume==a||b.isActive!="flash"))b.volume=
a,h(b._elem,"volumechange")}}else if(c.volume.prop._supset)return c.volume.prop._supset.apply(this,arguments)});i("muted",function(a){var b=n(this);if(b){if(a=!!a,x(this,"sendEvent",["mute",""+a],b),!(b.muted==a||b.isActive!="flash"))b.muted=a,h(b._elem,"volumechange")}else if(c.muted.prop._supset)return c.muted.prop._supset.apply(this,arguments)});i("currentTime",function(a){var b=n(this);if(b){if(a*=1,!isNaN(a)){if(b.paused)clearTimeout(b.stopPlayPause),b.stopPlayPause=setTimeout(function(){b.paused=
!0;b.stopPlayPause=!1},50);x(this,"sendEvent",["SEEK",""+a],b);if(b.paused){if(b.readyState>0)b.currentTime=a,h(b._elem,"timeupdate");try{b.jwapi.sendEvent("play","false")}catch(d){}}}}else if(c.currentTime.prop._supset)return c.currentTime.prop._supset.apply(this,arguments)});["play","pause"].forEach(function(a){b[a]={value:function(){var b=n(this);if(b)b.stopPlayPause&&clearTimeout(b.stopPlayPause),x(this,"sendEvent",["play",a=="play"],b),setTimeout(function(){if(b.isActive=="flash"&&(b._ppFlag=
!0,b.paused!=(a!="play")))b.paused=a!="play",h(b._elem,a)},1);else if(c[a].prop._supvalue)return c[a].prop._supvalue.apply(this,arguments)}}});g.forEach(d);f.onNodeNamesPropertyModify(a,"controls",function(b,c){var d=n(this);e(this)[c?"addClass":"removeClass"]("webshims-controls");if(d){try{x(this,c?"showControls":"hideControls",[a],d)}catch(i){f.warn("you need to generate a crossdomain.xml")}a=="audio"&&I(d,c);e(d.jwapi).attr("tabindex",c?"0":"-1")}});c=f.defineNodeNameProperties(a,b,"prop")});if(F){var N=
e.cleanData,O=e.browser.msie&&f.browserVersion<9,P={object:1,OBJECT:1};e.cleanData=function(a){var b,c,d;if(a&&(c=a.length)&&t)for(b=0;b<c;b++)if(P[a[b].nodeName]){if("sendEvent"in a[b]){t--;try{a[b].sendEvent("play",!1)}catch(e){}}if(O)try{for(d in a[b])typeof a[b][d]=="function"&&(a[b][d]=null)}catch(i){}}return N.apply(this,arguments)}}});
(function(e,f,o){var s=f.audio&&f.video,m=!1;if(s){var k=document.createElement("video");f.videoBuffered="buffered"in k;m="loop"in k;o.capturingEvents("play,playing,waiting,paused,ended,durationchange,loadedmetadata,canplay,volumechange".split(","));f.videoBuffered||(o.addPolyfill("mediaelement-native-fix",{feature:"mediaelement",test:f.videoBuffered,dependencies:["dom-support"]}),o.cfg.waitReady&&e.readyWait++,o.loader.loadScript("mediaelement-native-fix",function(){o.cfg.waitReady&&e.ready(!0)}))}e.webshims.ready("dom-support swfobject",
function(e,f,k,o,t){var g=f.mediaelement,A=f.cfg.mediaelement,y=function(c,d){var c=e(c),l={src:c.attr("src")||"",elem:c,srcProp:c.prop("src")};if(!l.src)return l;var j=c.attr("type");if(j)l.type=j,l.container=e.trim(j.split(";")[0]);else if(d||(d=c[0].nodeName.toLowerCase(),d=="source"&&(d=(c.closest("video, audio")[0]||{nodeName:"video"}).nodeName.toLowerCase())),j=g.getTypeForSrc(l.src,d))l.type=j,l.container=j,f.warn("you should always provide a proper mime-type using the source element. "+l.src+
" detected as: "+j),e.nodeName(c[0],"source")&&c.attr("type",j);if(j=c.attr("media"))l.media=j;return l},w=swfobject.hasFlashPlayerVersion("9.0.115"),r=function(){f.ready("mediaelement-swf",function(){if(!g.createSWF)f.modules["mediaelement-swf"].test=!1,delete e.event.special["mediaelement-swfReady"],f.loader.loadList(["mediaelement-swf"])})};w&&f.ready("WINDOWLOAD",r);g.mimeTypes={audio:{"audio/ogg":["ogg","oga","ogm"],"audio/mpeg":["mp2","mp3","mpga","mpega"],"audio/mp4":"mp4,mpg4,m4r,m4a,m4p,m4b,aac".split(","),
"audio/wav":["wav"],"audio/3gpp":["3gp","3gpp"],"audio/webm":["webm"],"audio/fla":["flv","f4a","fla"],"application/x-mpegURL":["m3u8","m3u"]},video:{"video/ogg":["ogg","ogv","ogm"],"video/mpeg":["mpg","mpeg","mpe"],"video/mp4":["mp4","mpg4","m4v"],"video/quicktime":["mov","qt"],"video/x-msvideo":["avi"],"video/x-ms-asf":["asf","asx"],"video/flv":["flv","f4v"],"video/3gpp":["3gp","3gpp"],"video/webm":["webm"],"application/x-mpegURL":["m3u8","m3u"],"video/MP2T":["ts"]}};g.mimeTypes.source=e.extend({},
g.mimeTypes.audio,g.mimeTypes.video);g.getTypeForSrc=function(c,d){if(c.indexOf("youtube.com/watch?")!=-1)return"video/youtube";var c=c.split("?")[0].split("."),c=c[c.length-1],f;e.each(g.mimeTypes[d],function(d,e){if(e.indexOf(c)!==-1)return f=d,!1});return f};g.srces=function(c,d){c=e(c);if(d)c.removeAttr("src").removeAttr("type").find("source").remove(),e.isArray(d)||(d=[d]),d.forEach(function(d){var e=o.createElement("source");typeof d=="string"&&(d={src:d});e.setAttribute("src",d.src);d.type&&
e.setAttribute("type",d.type);d.media&&e.setAttribute("media",d.media);c.append(e)});else{var d=[],f=c[0].nodeName.toLowerCase(),j=y(c,f);j.src?d.push(j):e("source",c).each(function(){j=y(this,f);j.src&&d.push(j)});return d}};e.fn.loadMediaSrc=function(c,d){return this.each(function(){d!==t&&(e(this).removeAttr("poster"),d&&e.attr(this,"poster",d));g.srces(this,c);e(this).mediaLoad()})};g.swfMimeTypes="video/3gpp,video/x-msvideo,video/quicktime,video/x-m4v,video/mp4,video/m4p,video/x-flv,video/flv,audio/mpeg,audio/aac,audio/mp4,audio/x-m4a,audio/m4a,audio/mp3,audio/x-fla,audio/fla,youtube/flv,jwplayer/jwplayer,video/youtube".split(",");
g.canSwfPlaySrces=function(c,d){var f="";w&&(c=e(c),d=d||g.srces(c),e.each(d,function(c,d){if(d.container&&d.src&&g.swfMimeTypes.indexOf(d.container)!=-1)return f=d,!1}));return f};var n={};g.canNativePlaySrces=function(c,d){var f="";if(s){var c=e(c),j=(c[0].nodeName||"").toLowerCase();if(!n[j])return f;d=d||g.srces(c);e.each(d,function(d,e){if(e.type&&n[j].prop._supvalue.call(c[0],e.type))return f=e,!1})}return f};g.setError=function(c,d){d||(d="can't play sources");e(c).pause().data("mediaerror",
d);f.warn("mediaelementError: "+d);setTimeout(function(){e(c).data("mediaerror")&&e(c).trigger("mediaerror")},1)};var h=function(){var c;return function(d,e,j){f.ready("mediaelement-swf",function(){g.createSWF?g.createSWF(d,e,j):c||(c=!0,r(),h(d,e,j))})}}(),z=function(c,d,e,f,i){e||e!==!1&&d&&d.isActive=="flash"?(e=g.canSwfPlaySrces(c,f))?h(c,e,d):i?g.setError(c,!1):z(c,d,!1,f,!0):(e=g.canNativePlaySrces(c,f))?d&&d.isActive=="flash"&&g.setActive(c,"html5",d):i?(g.setError(c,!1),d&&d.isActive=="flash"&&
g.setActive(c,"html5",d)):z(c,d,!0,f,!0)},B=/^(?:embed|object)$/i,v=function(c,d){var l=f.data(c,"mediaelementBase")||f.data(c,"mediaelementBase",{}),j=g.srces(c),h=c.parentNode;clearTimeout(l.loadTimer);e.data(c,"mediaerror",!1);if(j.length&&h&&!B.test(h.nodeName||""))d=d||f.data(c,"mediaelement"),z(c,d,A.preferFlash||t,j)};e(o).bind("ended",function(c){var d=f.data(c.target,"mediaelement");(!m||d&&d.isActive!="html5"||e.prop(c.target,"loop"))&&setTimeout(function(){!e.prop(c.target,"paused")&&e.prop(c.target,
"loop")&&e(c.target).prop("currentTime",0).play()},1)});m||f.defineNodeNamesBooleanProperty(["audio","video"],"loop");["audio","video"].forEach(function(c){var d=f.defineNodeNameProperty(c,"load",{prop:{value:function(){var c=f.data(this,"mediaelement");v(this,c);s&&(!c||c.isActive=="html5")&&d.prop._supvalue&&d.prop._supvalue.apply(this,arguments)}}});n[c]=f.defineNodeNameProperty(c,"canPlayType",{prop:{value:function(d){var f="";s&&n[c].prop._supvalue&&(f=n[c].prop._supvalue.call(this,d),f=="no"&&
(f=""));!f&&w&&(d=e.trim((d||"").split(";")[0]),g.swfMimeTypes.indexOf(d)!=-1&&(f="maybe"));return f}}})});f.onNodeNamesPropertyModify(["audio","video"],["src","poster"],{set:function(){var c=this,d=f.data(c,"mediaelementBase")||f.data(c,"mediaelementBase",{});clearTimeout(d.loadTimer);d.loadTimer=setTimeout(function(){v(c);c=null},9)}});f.addReady(function(c,d){e("video, audio",c).add(d.filter("video, audio")).each(function(){e.browser.msie&&f.browserVersion>8&&e.prop(this,"paused")&&!e.prop(this,
"readyState")&&e(this).is('audio[preload="none"][controls]:not([autoplay])')?e(this).prop("preload","metadata").mediaLoad():v(this);if(s){var c,d,g=this,h=function(){var c=e.prop(g,"buffered");if(c){for(var d="",f=0,h=c.length;f<h;f++)d+=c.end(f);return d}},k=function(){var c=h();c!=d&&(d=c,e(g).triggerHandler("progress"))};e(this).bind("play loadstart progress",function(e){e.type=="progress"&&(d=h());clearTimeout(c);c=setTimeout(k,999)}).bind("emptied stalled mediaerror abort suspend",function(e){e.type==
"emptied"&&(d=!1);clearTimeout(c)})}})});f.isReady("mediaelement-core",!0)})})(jQuery,Modernizr,jQuery.webshims);