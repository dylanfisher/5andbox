(()=>{var we=Object.create;var q=Object.defineProperty;var me=Object.getOwnPropertyDescriptor;var he=Object.getOwnPropertyNames;var Te=Object.getPrototypeOf,ve=Object.prototype.hasOwnProperty;var o=(e,n)=>()=>(n||e((n={exports:{}}).exports,n),n.exports);var xe=(e,n,r,i)=>{if(n&&typeof n=="object"||typeof n=="function")for(let t of he(n))!ve.call(e,t)&&t!==r&&q(e,t,{get:()=>n[t],enumerable:!(i=me(n,t))||i.enumerable});return e};var F=(e,n,r)=>(r=e!=null?we(Te(e)):{},xe(n||!e||!e.__esModule?q(r,"default",{value:e,enumerable:!0}):r,e));var w=o((wn,R)=>{function ye(e){var n=typeof e;return e!=null&&(n=="object"||n=="function")}R.exports=ye});var C=o((mn,E)=>{var $e=typeof global=="object"&&global&&global.Object===Object&&global;E.exports=$e});var v=o((hn,I)=>{var je=C(),Se=typeof self=="object"&&self&&self.Object===Object&&self,Oe=je||Se||Function("return this")();I.exports=Oe});var B=o((Tn,L)=>{var We=v(),ke=function(){return We.Date.now()};L.exports=ke});var M=o((vn,N)=>{var qe=/\s/;function Fe(e){for(var n=e.length;n--&&qe.test(e.charAt(n)););return n}N.exports=Fe});var D=o((xn,P)=>{var Re=M(),Ee=/^\s+/;function Ce(e){return e&&e.slice(0,Re(e)+1).replace(Ee,"")}P.exports=Ce});var x=o((yn,G)=>{var Ie=v(),Le=Ie.Symbol;G.exports=Le});var U=o(($n,H)=>{var _=x(),z=Object.prototype,Be=z.hasOwnProperty,Ne=z.toString,g=_?_.toStringTag:void 0;function Me(e){var n=Be.call(e,g),r=e[g];try{e[g]=void 0;var i=!0}catch{}var t=Ne.call(e);return i&&(n?e[g]=r:delete e[g]),t}H.exports=Me});var J=o((jn,X)=>{var Pe=Object.prototype,De=Pe.toString;function Ge(e){return De.call(e)}X.exports=Ge});var Y=o((Sn,V)=>{var K=x(),_e=U(),ze=J(),He="[object Null]",Ue="[object Undefined]",Q=K?K.toStringTag:void 0;function Xe(e){return e==null?e===void 0?Ue:He:Q&&Q in Object(e)?_e(e):ze(e)}V.exports=Xe});var ee=o((On,Z)=>{function Je(e){return e!=null&&typeof e=="object"}Z.exports=Je});var re=o((Wn,ne)=>{var Ke=Y(),Qe=ee(),Ve="[object Symbol]";function Ye(e){return typeof e=="symbol"||Qe(e)&&Ke(e)==Ve}ne.exports=Ye});var oe=o((kn,pe)=>{var Ze=D(),te=w(),en=re(),ie=0/0,nn=/^[-+]0x[0-9a-f]+$/i,rn=/^0b[01]+$/i,tn=/^0o[0-7]+$/i,pn=parseInt;function on(e){if(typeof e=="number")return e;if(en(e))return ie;if(te(e)){var n=typeof e.valueOf=="function"?e.valueOf():e;e=te(n)?n+"":n}if(typeof e!="string")return e===0?e:+e;e=Ze(e);var r=rn.test(e);return r||tn.test(e)?pn(e.slice(2),r?2:8):nn.test(e)?ie:+e}pe.exports=on});var j=o((qn,ue)=>{var an=w(),y=B(),ae=oe(),un="Expected a function",dn=Math.max,sn=Math.min;function cn(e,n,r){var i,t,a,c,u,s,l=0,S=!1,f=!1,m=!0;if(typeof e!="function")throw new TypeError(un);n=ae(n)||0,an(r)&&(S=!!r.leading,f="maxWait"in r,a=f?dn(ae(r.maxWait)||0,n):a,m="trailing"in r?!!r.trailing:m);function h(p){var d=i,A=t;return i=t=void 0,l=p,c=e.apply(A,d),c}function fe(p){return l=p,u=setTimeout(b,n),S?h(p):c}function Ae(p){var d=p-s,A=p-l,k=n-d;return f?sn(k,a-A):k}function O(p){var d=p-s,A=p-l;return s===void 0||d>=n||d<0||f&&A>=a}function b(){var p=y();if(O(p))return W(p);u=setTimeout(b,Ae(p))}function W(p){return u=void 0,m&&i?h(p):(i=t=void 0,c)}function ge(){u!==void 0&&clearTimeout(u),l=0,i=s=t=u=void 0}function be(){return u===void 0?c:W(y())}function T(){var p=y(),d=O(p);if(i=arguments,t=this,s=p,d){if(u===void 0)return fe(s);if(f)return clearTimeout(u),u=setTimeout(b,n),h(s)}return u===void 0&&(u=setTimeout(b,n)),c}return T.cancel=ge,T.flush=be,T}ue.exports=cn});var se=o((Fn,de)=>{var ln=j(),fn=w(),An="Expected a function";function gn(e,n,r){var i=!0,t=!0;if(typeof e!="function")throw new TypeError(An);return fn(r)&&(i="leading"in r?!!r.leading:i,t="trailing"in r?!!r.trailing:t),ln(e,n,{leading:i,maxWait:n,trailing:t})}de.exports=gn});var ce=F(se()),le=F(j());window.App=window.App||{};App.$window=$(window);App.$document=$(document);App.pageLoad=[];App.pageResize=[];App.pageScroll=[];App.pageThrottledScroll=[];App.pageDebouncedResize=[];App.breakpointChange=[];App.teardown=[];App.runFunctions=function(e){for(var n=e.length-1;n>=0;n--)e[n]()};App.currentBreakpoint=void 0;$(function(){App.scrollTop=App.$window.scrollTop(),App.windowWidth=App.$window.width(),App.windowHeight=App.$window.height(),App.$html=$("html"),App.$body=$("body"),App.$header=$("#header"),App.$html.removeClass("no-js"),App.currentBreakpoint=App.breakpoint(),App.runFunctions(App.pageLoad),App.runFunctions(App.pageResize),App.runFunctions(App.pageDebouncedResize),App.runFunctions(App.pageScroll),App.runFunctions(App.pageThrottledScroll),window.setTimeout(function(){App.$html.removeClass("js-preload"),App.$document.trigger("app:delayed-page-load")},200)});App.$window.on("scroll",function(){App.scrollTop=App.$window.scrollTop(),App.runFunctions(App.pageScroll)});App.$window.on("scroll",(0,ce.default)(function(){App.runFunctions(App.pageThrottledScroll)},200));App.$window.on("resize",function(){App.windowWidth=App.$window.width(),App.windowHeight=App.$window.height(),App.currentBreakpoint!=App.breakpoint()&&App.$document.trigger("app:breakpoint-change"),App.currentBreakpoint=App.breakpoint(),App.runFunctions(App.pageResize)});App.$window.on("resize",(0,le.default)(function(){App.runFunctions(App.pageDebouncedResize)},500));App.$document.on("app:breakpoint-change",function(){App.runFunctions(App.breakpointChange)});App.breakpoint=function(e){var n=576,r=768,i=1100,t=1400,a;if(App.windowWidth<n?a="xs":App.windowWidth>=t?a="xl":App.windowWidth>=i?a="lg":App.windowWidth>=r?a="md":a="sm",e!==void 0){if(e=="xs")return App.windowWidth<n;if(e=="sm")return App.windowWidth>=n&&App.windowWidth<r;if(e=="md")return App.windowWidth>=r&&App.windowWidth<i;if(e=="lg")return App.windowWidth>=i&&App.windowWidth<t;if(e=="xl")return App.windowWidth>=t}else return a};App.breakpoint.isMobile=function(){return App.breakpoint("xs")||App.breakpoint("sm")};})();
