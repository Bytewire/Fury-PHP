<?php
	include dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."config.php";
?>

/*	flXHR 1.0.6 <http://flxhr.flensed.com/> | Copyright (c) 2008-2010 Kyle Simpson, Getify Solutions, Inc. | This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> */

(function(c){
	var baseUrl = '<?php echo BASE_URL;?>crossdomain/';
	var E=c,h=c.document,z="undefined",a=true,L=false,g="",o="object",k="function",N="string",l="div",e="onunload",H=null,y=null,K=null,q=null,x=0,i=[],m=null,r=null,G=baseUrl+"flXHR.js",n=baseUrl+"flensed.php",P=baseUrl+"flXHR.vbs",j=baseUrl+"checkplayer.php",A=baseUrl+"flXHR.swf",u=c.parseInt,w=c.setTimeout,f=c.clearTimeout,s=c.setInterval,v=c.clearInterval,O="instanceId",J="readyState",D="onreadystatechange",M="ontimeout",C="onerror",d="binaryResponseBody",F="xmlResponseText",I="loadPolicyURL",b="noCacheHeader",p="sendTimeout",B="appendToId",t="swfIdPrefix";if(typeof c.flensed===z){c.flensed={}}if(typeof c.flensed.flXHR!==z){return}y=c.flensed;w(function(){var Q=L,ab=h.getElementsByTagName("script"),V=ab.length;try{y.base_path.toLowerCase();Q=a}catch(T){y.base_path=g}function Z(ai,ah,aj){for(var ag=0;ag<V;ag++){if(typeof ab[ag].src!==z){if(ab[ag].src.indexOf(ai)>=0){break}}}var af=h.createElement("script");af.setAttribute("src",y.base_path+ai);if(typeof ah!==z){af.setAttribute("type",ah)}if(typeof aj!==z){af.setAttribute("language",aj)}h.getElementsByTagName("head")[0].appendChild(af)}if((typeof ab!==z)&&(ab!==null)){if(!Q){var ac=0;for(var U=0;U<V;U++){if(typeof ab[U].src!==z){if(((ac=ab[U].src.indexOf(n))>=0)||((ac=ab[U].src.indexOf(G))>=0)){y.base_path=ab[U].src.substr(0,ac);break}}}}}try{y.checkplayer.module_ready()}catch(aa){Z(j,"text/javascript")}var ad=null;(function ae(){try{y.ua.pv.join(".")}catch(af){ad=w(arguments.callee,25);return}if(y.ua.win&&y.ua.ie){Z(P,"text/vbscript","vbscript")}y.binaryToString=function(aj,ai){ai=(((y.ua.win&&y.ua.ie)&&typeof ai!==z)?(!(!ai)):!(y.ua.win&&y.ua.ie));if(!ai){try{return flXHR_vb_BinaryToString(aj)}catch(al){}}var am=g,ah=[];try{for(var ak=0;ak<aj.length;ak++){ah[ah.length]=String.fromCharCode(aj[ak])}am=ah.join(g)}catch(ag){}return am};y.bindEvent(E,e,function(){try{c.flensed.unbindEvent(E,e,arguments.callee);for(var ai in r){if(r[ai]!==Object.prototype[ai]){try{r[ai]=null}catch(ah){}}}y.flXHR=null;r=null;y=null;q=null;K=null}catch(ag){}})})();function Y(){f(ad);try{E.detachEvent(e,Y)}catch(af){}}if(ad!==null){try{E.attachEvent(e,Y)}catch(X){}}var S=null;function R(){f(S);try{E.detachEvent(e,R)}catch(af){}}try{E.attachEvent(e,R)}catch(W){}S=w(function(){R();try{y.checkplayer.module_ready()}catch(af){throw new c.Error("flXHR dependencies failed to load.")}},20000)},0);y.flXHR=function(aR){var ab=L;if(aR!==null&&typeof aR===o){if(typeof aR.instancePooling!==z){ab=!(!aR.instancePooling);if(ab){var aG=function(){for(var a0=0;a0<i.length;a0++){var a1=i[a0];if(a1[J]===4){a1.Reset();a1.Configure(aR);return a1}}return null}();if(aG!==null){return aG}}}}var aW=++x,ai=[],af=null,ah=null,X=null,Y=null,aM=-1,aF=0,aa=null,ac=null,ao=null,aE=null,aw=null,aV=null,ak=null,Q=null,aL=null,Z=a,aB=L,aY="flXHR_"+aW,au=a,aC=L,aA=a,aJ=L,S="flXHR_swf",ae="flXHRhideSwf",V=null,aH=-1,T=g,aK=null,aD=null,aO=null;var U=function(){if(typeof aR===o&&aR!==null){if((typeof aR[O]!==z)&&(aR[O]!==null)&&(aR[O]!==g)){aY=aR[O]}if((typeof aR[t]!==z)&&(aR[t]!==null)&&(aR[t]!==g)){S=aR[t]}if((typeof aR[B]!==z)&&(aR[B]!==null)&&(aR[B]!==g)){V=aR[B]}if((typeof aR[I]!==z)&&(aR[I]!==null)&&(aR[I]!==g)){T=aR[I]}if(typeof aR[b]!==z){au=!(!aR[b])}if(typeof aR[d]!==z){aC=!(!aR[d])}if(typeof aR[F]!==z){aA=!(!aR[F])}if(typeof aR.autoUpdatePlayer!==z){aJ=!(!aR.autoUpdatePlayer)}if((typeof aR[p]!==z)&&((H=u(aR[p],10))>0)){aH=H}if((typeof aR[D]!==z)&&(aR[D]!==null)){aK=aR[D]}if((typeof aR[C]!==z)&&(aR[C]!==null)){aD=aR[C]}if((typeof aR[M]!==z)&&(aR[M]!==null)){aO=aR[M]}}Y=S+"_"+aW;function a0(){f(af);try{E.detachEvent(e,a0)}catch(a3){}}try{E.attachEvent(e,a0)}catch(a1){}(function a2(){try{y.bindEvent(E,e,aI)}catch(a3){af=w(arguments.callee,25);return}a0();af=w(aT,1)})()}();function aT(){if(V===null){Q=h.getElementsByTagName("body")[0]}else{Q=y.getObjectById(V)}try{Q.nodeName.toLowerCase();y.checkplayer.module_ready();K=y.checkplayer}catch(a1){af=w(aT,25);return}if((q===null)&&(typeof K._ins===z)){try{q=new K(r.MIN_PLAYER_VERSION,aU,L,aq)}catch(a0){aP(r.DEPENDENCY_ERROR,"flXHR: checkplayer Init Failed","The initialization of the 'checkplayer' library failed to complete.");return}}else{q=K._ins;ag()}}function ag(){if(q===null||!q.checkPassed){af=w(ag,25);return}if(m===null&&V===null){y.createCSS("."+ae,"left:-1px;top:0px;width:1px;height:1px;position:absolute;");m=a}var a4=h.createElement(l);a4.id=Y;a4.className=ae;Q.appendChild(a4);Q=null;var a1={},a5={allowScriptAccess:"always"},a2={id:Y,name:Y,styleclass:ae},a3={swfCB:aS,swfEICheck:"reset"};try{q.DoSWF(y.base_path+A,Y,"1","1",a1,a5,a2,a3)}catch(a0){aP(r.DEPENDENCY_ERROR,"flXHR: checkplayer Call Failed","A call to the 'checkplayer' library failed to complete.");return}}function aS(a0){if(a0.status!==K.SWF_EI_READY){return}R();aV=y.getObjectById(Y);aV.setId(Y);if(T!==g){aV.loadPolicy(T)}aV.autoNoCacheHeader(au);aV.returnBinaryResponseBody(aC);aV.doOnReadyStateChange=al;aV.doOnError=aP;aV.sendProcessed=ap;aV.chunkResponse=ay;aM=0;ax();aX();if(typeof aK===k){try{aK(ak)}catch(a1){aP(r.HANDLER_ERROR,"flXHR::onreadystatechange(): Error","An error occurred in the handler function. ("+a1.message+")");return}}at()}function aI(){try{c.flensed.unbindEvent(E,e,aI)}catch(a3){}try{for(var a4=0;a4<i.length;a4++){if(i[a4]===ak){i[a4]=L}}}catch(bb){}try{for(var a6 in ak){if(ak[a6]!==Object.prototype[a6]){try{ak[a6]=null}catch(ba){}}}}catch(a9){}ak=null;R();if((typeof aV!==z)&&(aV!==null)){try{aV.abort()}catch(a8){}try{aV.doOnReadyStateChange=null;al=null}catch(a7){}try{aV.doOnError=null;doOnError=null}catch(a5){}try{aV.sendProcessed=null;ap=null}catch(a2){}try{aV.chunkResponse=null;ay=null}catch(a1){}aV=null;try{c.swfobject.removeSWF(Y)}catch(a0){}}aQ();aK=null;aD=null;aO=null;ao=null;aa=null;aL=null;Q=null}function ay(){if(aC&&typeof arguments[0]!==z){aL=((aL!==null)?aL:[]);aL=aL.concat(arguments[0])}else{if(typeof arguments[0]===N){aL=((aL!==null)?aL:g);aL+=arguments[0]}}}function al(){if(typeof arguments[0]!==z){aM=arguments[0]}if(aM===4){R();if(aC&&aL!==null){try{ac=y.binaryToString(aL,a);try{aa=flXHR_vb_StringToBinary(ac)}catch(a2){aa=aL}}catch(a1){}}else{ac=aL}aL=null;if(ac!==g){if(aA){try{ao=y.parseXMLString(ac)}catch(a0){ao={}}}}}if(typeof arguments[1]!==z){aE=arguments[1]}if(typeof arguments[2]!==z){aw=arguments[2]}ad(aM)}function ad(a0){aF=a0;ax();aX();ak[J]=Math.max(0,a0);if(typeof aK===k){try{aK(ak)}catch(a1){aP(r.HANDLER_ERROR,"flXHR::onreadystatechange(): Error","An error occurred in the handler function. ("+a1.message+")");return}}}function aP(){R();aQ();aB=a;var a3;try{a3=new y.error(arguments[0],arguments[1],arguments[2],ak)}catch(a4){function a1(){this.number=0;this.name="flXHR Error: Unknown";this.description="Unknown error from 'flXHR' library.";this.message=this.description;this.srcElement=ak;var a8=this.number,a7=this.name,ba=this.description;function a9(){return a8+", "+a7+", "+ba}this.toString=a9}a3=new a1()}var a5=L;try{if(typeof aD===k){aD(a3);a5=a}}catch(a0){var a2=a3.toString();function a6(){this.number=r.HANDLER_ERROR;this.name="flXHR::onerror(): Error";this.description="An error occured in the handler function. ("+a0.message+")\nPrevious:["+a2+"]";this.message=this.description;this.srcElement=ak;var a8=this.number,a7=this.name,ba=this.description;function a9(){return a8+", "+a7+", "+ba}this.toString=a9}a3=new a6()}if(!a5){w(function(){y.throwUnhandledError(a3.toString())},1)}}function W(){am();aB=a;if(typeof aO===k){try{aO(ak)}catch(a0){aP(r.HANDLER_ERROR,"flXHR::ontimeout(): Error","An error occurred in the handler function. ("+a0.message+")");return}}else{aP(r.TIMEOUT_ERROR,"flXHR: Operation Timed out","The requested operation timed out.")}}function R(){f(af);af=null;f(X);X=null;f(ah);ah=null}function aZ(a1,a2,a0){ai[ai.length]={func:a1,funcName:a2,args:a0};Z=L}function aQ(){if(!Z){Z=a;var a1=ai.length;for(var a0=0;a0<a1;a0++){try{ai[a0]=L}catch(a2){}}ai=[]}}function at(){if(aM<0){ah=w(at,25);return}if(!Z){for(var a0=0;a0<ai.length;a0++){try{if(ai[a0]!==L){ai[a0].func.apply(ak,ai[a0].args);ai[a0]=L}}catch(a1){aP(r.HANDLER_ERROR,"flXHR::"+ai[a0].funcName+"(): Error","An error occurred in the "+ai[a0].funcName+"() function.");return}}Z=a}}function aX(){try{ak[O]=aY;ak[J]=aF;ak.status=aE;ak.statusText=aw;ak.responseText=ac;ak.responseXML=ao;ak.responseBody=aa;ak[D]=aK;ak[C]=aD;ak[M]=aO;ak[I]=T;ak[b]=au;ak[d]=aC;ak[F]=aA}catch(a0){}}function ax(){try{aY=ak[O];if(ak.timeout!==null&&(H=u(ak.timeout,10))>0){aH=H}aK=ak[D];aD=ak[C];aO=ak[M];if(ak[I]!==null){if((ak[I]!==T)&&(aM>=0)){aV.loadPolicy(ak[I])}T=ak[I]}if(ak[b]!==null){if((ak[b]!==au)&&(aM>=0)){aV.autoNoCacheHeader(ak[b])}au=ak[b]}if(ak[d]!==null){if((ak[d]!==aC)&&(aM>=0)){aV.returnBinaryResponseBody(ak[d])}aC=ak[d]}if(aA!==null){aA=!(!ak[F])}}catch(a0){}}function aN(){am();try{aV.reset()}catch(a0){}aE=null;aw=null;ac=null;ao=null;aa=null;aL=null;aB=L;aX();T=g;ax()}function aU(a0){if(a0.checkPassed){ag()}else{if(!aJ){aP(r.PLAYER_VERSION_ERROR,"flXHR: Insufficient Flash Player Version","The Flash Player was either not detected, or the detected version ("+a0.playerVersionDetected+") was not at least the minimum version ("+r.MIN_PLAYER_VERSION+") needed by the 'flXHR' library.")}else{q.UpdatePlayer()}}}function aq(a0){if(a0.updateStatus===K.UPDATE_CANCELED){aP(r.PLAYER_VERSION_ERROR,"flXHR: Flash Player Update Canceled","The Flash Player was not updated.")}else{if(a0.updateStatus===K.UPDATE_FAILED){aP(r.PLAYER_VERSION_ERROR,"flXHR: Flash Player Update Failed","The Flash Player was either not detected or could not be updated.")}}}function ap(){if(aH!==null&&aH>0){X=w(W,aH)}}function am(){R();aQ();ax();aM=0;aF=0;try{aV.abort()}catch(a0){aP(r.CALL_ERROR,"flXHR::abort(): Failed","The abort() call failed to complete.")}aX()}function av(){ax();if(typeof arguments[0]===z||typeof arguments[1]===z){aP(r.CALL_ERROR,"flXHR::open(): Failed","The open() call requires 'method' and 'url' parameters.")}else{if(aM>0||aB){aN()}if(aF===0){al(1)}else{aM=1}var a7=arguments[0],a6=arguments[1],a5=(typeof arguments[2]!==z)?arguments[2]:a,ba=(typeof arguments[3]!==z)?arguments[3]:g,a9=(typeof arguments[4]!==z)?arguments[4]:g;try{aV.autoNoCacheHeader(au);aV.open(a7,a6,a5,ba,a9)}catch(a8){aP(r.CALL_ERROR,"flXHR::open(): Failed","The open() call failed to complete.")}}}function az(){ax();if(aM<=1&&!aB){var a1=(typeof arguments[0]!==z)?arguments[0]:g;if(aF===1){al(2)}else{aM=2}try{aV.autoNoCacheHeader(au);aV.send(a1)}catch(a2){aP(r.CALL_ERROR,"flXHR::send(): Failed","The send() call failed to complete.")}}else{aP(r.CALL_ERROR,"flXHR::send(): Failed","The send() call cannot be made at this time.")}}function aj(){ax();if(typeof arguments[0]===z||typeof arguments[1]===z){aP(r.CALL_ERROR,"flXHR::setRequestHeader(): Failed","The setRequestHeader() call requires 'name' and 'value' parameters.")}else{if(!aB){var a3=(typeof arguments[0]!==z)?arguments[0]:g,a2=(typeof arguments[1]!==z)?arguments[1]:g;try{aV.setRequestHeader(a3,a2)}catch(a4){aP(r.CALL_ERROR,"flXHR::setRequestHeader(): Failed","The setRequestHeader() call failed to complete.")}}}}function an(){ax();return g}function ar(){ax();return[]}ak={readyState:aF,responseBody:aa,responseText:ac,responseXML:ao,status:aE,statusText:aw,timeout:aH,open:function(){ax();if(ak[J]===0){ad(1)}if(!Z||aM<0){aZ(av,"open",arguments);return}av.apply({},arguments)},send:function(){ax();if(ak[J]===1){ad(2)}if(!Z||aM<0){aZ(az,"send",arguments);return}az.apply({},arguments)},abort:am,setRequestHeader:function(){ax();if(!Z||aM<0){aZ(aj,"setRequestHeader",arguments);return}aj.apply({},arguments)},getResponseHeader:an,getAllResponseHeaders:ar,onreadystatechange:aK,ontimeout:aO,instanceId:aY,loadPolicyURL:T,noCacheHeader:au,binaryResponseBody:aC,xmlResponseText:aA,onerror:aD,Configure:function(a0){if(typeof a0===o&&a0!==null){if((typeof a0[O]!==z)&&(a0[O]!==null)&&(a0[O]!==g)){aY=a0[O]}if(typeof a0[b]!==z){au=!(!a0[b]);if(aM>=0){aV.autoNoCacheHeader(au)}}if(typeof a0[d]!==z){aC=!(!a0[d]);if(aM>=0){aV.returnBinaryResponseBody(aC)}}if(typeof a0[F]!==z){aA=!(!a0[F])}if((typeof a0[D]!==z)&&(a0[D]!==null)){aK=a0[D]}if((typeof a0[C]!==z)&&(a0[C]!==null)){aD=a0[C]}if((typeof a0[M]!==z)&&(a0[M]!==null)){aO=a0[M]}if((typeof a0[p]!==z)&&((H=u(a0[p],10))>0)){aH=H}if((typeof a0[I]!==z)&&(a0[I]!==null)&&(a0[I]!==g)&&(a0[I]!==T)){T=a0[I];if(aM>=0){aV.loadPolicy(T)}}aX()}},Reset:aN,Destroy:aI};if(ab){i[i.length]=ak}return ak};r=y.flXHR;r.HANDLER_ERROR=10;r.CALL_ERROR=11;r.TIMEOUT_ERROR=12;r.DEPENDENCY_ERROR=13;r.PLAYER_VERSION_ERROR=14;r.SECURITY_ERROR=15;r.COMMUNICATION_ERROR=16;r.MIN_PLAYER_VERSION="9.0.124";r.module_ready=function(){}})(window);


/**
 * jQuery.XHR
 * Copyright (c) 2008 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
 * Dual licensed under MIT and GPL.
 * Date: 8/7/2008
 *
 * @projectDescription Registry of XHR implementations
 *
 * @author Ariel Flesler
 * @version 1.0.0
 */

;(function( $ ){
	var as = $.ajaxSettings;
	$.xhr = {
		registry: {
			xhr: as.xhr	
		},
		register:function( name, fn ){
			this.registry[name] = fn;
		}
	};
	as.transport = 'xhr';
	as.xhr = function(){
		return $.xhr.registry[ this.transport ]( this );
	};
	
})(jqcc);


/*	
	jQuery.flXHRproxy 1.2.2 <http://flxhr.flensed.com/>
	Copyright (c) 2009 Kyle Simpson
	This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
*/

;(function( $ ){
	$.flXHRproxy = flensed.flXHR;
	var _opts = [], old_ajax = $.ajax;
	
	$.extend({
		ajax:function(s) {
			var old_success = s.success, my_xhr = null;
			s.success = function() { 
				var args = $.makeArray(arguments);
				args.splice(2,0,my_xhr);
				if (typeof old_success === "function") old_success.apply(null,args); 
			};
			my_xhr = old_ajax(s);
			return my_xhr;
		}
	});
	
	$.flXHRproxy.registerOptions = function(url,fopts) {
		if (typeof fopts==="undefined"||fopts===null) fopts = {};
		if (typeof fopts.instancePooling==="undefined"||fopts.instancePooling===null) fopts.instancePooling = true;
		if (typeof fopts.autoUpdatePlayer === "undefined"||fopts.autoUpdatePlayer===null) fopts.autoUpdatePlayer = true;
		_opts.push(function(callUrl) {
			if (callUrl.substring(0,url.length)===url) return fopts;
			else return null;
		});
	}
	$.xhr.register('flXHRproxy',function(as) {
		var tmp = null, useopts = null;
		if (as.async&&(as.type==="post"||as.type==="get"||as.type==="POST"||as.type==="GET")) {
			for (var i=0; i<_opts.length; i++) {
				if ((tmp=_opts[i](as.url))!==null) useopts = tmp;
			}
		}
		if (useopts !== null) {
			var old_onerror = useopts.onerror;
			useopts.onerror = function() {
				var errObj = arguments[0];
				var newErrObj = new Error(errObj);
				for (var i in errObj) 
					if (errObj[i]!==Object.prototype[i]) newErrObj[i] = errObj[i];
				if (typeof old_onerror === "function") old_onerror.call(as,newErrObj.srcElement,"error",newErrObj);
				$.handleError.call(as,as,newErrObj.srcElement,"error",newErrObj);
			};
			return new $.flXHRproxy(useopts);
		}
		else {
			return $.xhr.registry['xhr'](as);
		}
	});
})(jqcc);



