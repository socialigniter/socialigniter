/*!
 * Sizzle CSS Selector Engine
 *  Copyright 2011, The Dojo Foundation
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://sizzlejs.com/
 */
(function(){var chunker=/((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,done=0,toString=Object.prototype.toString,hasDuplicate=false,baseHasDuplicate=true,rBackslash=/\\/g,rNonWord=/\W/;[0,0].sort(function(){baseHasDuplicate=false;return 0;});var Sizzle=function(selector,context,results,seed){results=results||[];context=context||document;var origContext=context;if(context.nodeType!==1&&context.nodeType!==9){return[];}
if(!selector||typeof selector!=="string"){return results;}
var m,set,checkSet,extra,ret,cur,pop,i,prune=true,contextXML=Sizzle.isXML(context),parts=[],soFar=selector;do{chunker.exec("");m=chunker.exec(soFar);if(m){soFar=m[3];parts.push(m[1]);if(m[2]){extra=m[3];break;}}}while(m);if(parts.length>1&&origPOS.exec(selector)){if(parts.length===2&&Expr.relative[parts[0]]){set=posProcess(parts[0]+parts[1],context);}else{set=Expr.relative[parts[0]]?[context]:Sizzle(parts.shift(),context);while(parts.length){selector=parts.shift();if(Expr.relative[selector]){selector+=parts.shift();}
set=posProcess(selector,set);}}}else{if(!seed&&parts.length>1&&context.nodeType===9&&!contextXML&&Expr.match.ID.test(parts[0])&&!Expr.match.ID.test(parts[parts.length-1])){ret=Sizzle.find(parts.shift(),context,contextXML);context=ret.expr?Sizzle.filter(ret.expr,ret.set)[0]:ret.set[0];}
if(context){ret=seed?{expr:parts.pop(),set:makeArray(seed)}:Sizzle.find(parts.pop(),parts.length===1&&(parts[0]==="~"||parts[0]==="+")&&context.parentNode?context.parentNode:context,contextXML);set=ret.expr?Sizzle.filter(ret.expr,ret.set):ret.set;if(parts.length>0){checkSet=makeArray(set);}else{prune=false;}
while(parts.length){cur=parts.pop();pop=cur;if(!Expr.relative[cur]){cur="";}else{pop=parts.pop();}
if(pop==null){pop=context;}
Expr.relative[cur](checkSet,pop,contextXML);}}else{checkSet=parts=[];}}
if(!checkSet){checkSet=set;}
if(!checkSet){Sizzle.error(cur||selector);}
if(toString.call(checkSet)==="[object Array]"){if(!prune){results.push.apply(results,checkSet);}else if(context&&context.nodeType===1){for(i=0;checkSet[i]!=null;i++){if(checkSet[i]&&(checkSet[i]===true||checkSet[i].nodeType===1&&Sizzle.contains(context,checkSet[i]))){results.push(set[i]);}}}else{for(i=0;checkSet[i]!=null;i++){if(checkSet[i]&&checkSet[i].nodeType===1){results.push(set[i]);}}}}else{makeArray(checkSet,results);}
if(extra){Sizzle(extra,origContext,results,seed);Sizzle.uniqueSort(results);}
return results;};Sizzle.uniqueSort=function(results){if(sortOrder){hasDuplicate=baseHasDuplicate;results.sort(sortOrder);if(hasDuplicate){for(var i=1;i<results.length;i++){if(results[i]===results[i-1]){results.splice(i--,1);}}}}
return results;};Sizzle.matches=function(expr,set){return Sizzle(expr,null,null,set);};Sizzle.matchesSelector=function(node,expr){return Sizzle(expr,null,null,[node]).length>0;};Sizzle.find=function(expr,context,isXML){var set;if(!expr){return[];}
for(var i=0,l=Expr.order.length;i<l;i++){var match,type=Expr.order[i];if((match=Expr.leftMatch[type].exec(expr))){var left=match[1];match.splice(1,1);if(left.substr(left.length-1)!=="\\"){match[1]=(match[1]||"").replace(rBackslash,"");set=Expr.find[type](match,context,isXML);if(set!=null){expr=expr.replace(Expr.match[type],"");break;}}}}
if(!set){set=typeof context.getElementsByTagName!=="undefined"?context.getElementsByTagName("*"):[];}
return{set:set,expr:expr};};Sizzle.filter=function(expr,set,inplace,not){var match,anyFound,old=expr,result=[],curLoop=set,isXMLFilter=set&&set[0]&&Sizzle.isXML(set[0]);while(expr&&set.length){for(var type in Expr.filter){if((match=Expr.leftMatch[type].exec(expr))!=null&&match[2]){var found,item,filter=Expr.filter[type],left=match[1];anyFound=false;match.splice(1,1);if(left.substr(left.length-1)==="\\"){continue;}
if(curLoop===result){result=[];}
if(Expr.preFilter[type]){match=Expr.preFilter[type](match,curLoop,inplace,result,not,isXMLFilter);if(!match){anyFound=found=true;}else if(match===true){continue;}}
if(match){for(var i=0;(item=curLoop[i])!=null;i++){if(item){found=filter(item,match,i,curLoop);var pass=not^!!found;if(inplace&&found!=null){if(pass){anyFound=true;}else{curLoop[i]=false;}}else if(pass){result.push(item);anyFound=true;}}}}
if(found!==undefined){if(!inplace){curLoop=result;}
expr=expr.replace(Expr.match[type],"");if(!anyFound){return[];}
break;}}}
if(expr===old){if(anyFound==null){Sizzle.error(expr);}else{break;}}
old=expr;}
return curLoop;};Sizzle.error=function(msg){throw"Syntax error, unrecognized expression: "+msg;};var Expr=Sizzle.selectors={order:["ID","NAME","TAG"],match:{ID:/#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,CLASS:/\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,NAME:/\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,ATTR:/\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,TAG:/^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,CHILD:/:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,POS:/:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,PSEUDO:/:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/},leftMatch:{},attrMap:{"class":"className","for":"htmlFor"},attrHandle:{href:function(elem){return elem.getAttribute("href");},type:function(elem){return elem.getAttribute("type");}},relative:{"+":function(checkSet,part){var isPartStr=typeof part==="string",isTag=isPartStr&&!rNonWord.test(part),isPartStrNotTag=isPartStr&&!isTag;if(isTag){part=part.toLowerCase();}
for(var i=0,l=checkSet.length,elem;i<l;i++){if((elem=checkSet[i])){while((elem=elem.previousSibling)&&elem.nodeType!==1){}
checkSet[i]=isPartStrNotTag||elem&&elem.nodeName.toLowerCase()===part?elem||false:elem===part;}}
if(isPartStrNotTag){Sizzle.filter(part,checkSet,true);}},">":function(checkSet,part){var elem,isPartStr=typeof part==="string",i=0,l=checkSet.length;if(isPartStr&&!rNonWord.test(part)){part=part.toLowerCase();for(;i<l;i++){elem=checkSet[i];if(elem){var parent=elem.parentNode;checkSet[i]=parent.nodeName.toLowerCase()===part?parent:false;}}}else{for(;i<l;i++){elem=checkSet[i];if(elem){checkSet[i]=isPartStr?elem.parentNode:elem.parentNode===part;}}
if(isPartStr){Sizzle.filter(part,checkSet,true);}}},"":function(checkSet,part,isXML){var nodeCheck,doneName=done++,checkFn=dirCheck;if(typeof part==="string"&&!rNonWord.test(part)){part=part.toLowerCase();nodeCheck=part;checkFn=dirNodeCheck;}
checkFn("parentNode",part,doneName,checkSet,nodeCheck,isXML);},"~":function(checkSet,part,isXML){var nodeCheck,doneName=done++,checkFn=dirCheck;if(typeof part==="string"&&!rNonWord.test(part)){part=part.toLowerCase();nodeCheck=part;checkFn=dirNodeCheck;}
checkFn("previousSibling",part,doneName,checkSet,nodeCheck,isXML);}},find:{ID:function(match,context,isXML){if(typeof context.getElementById!=="undefined"&&!isXML){var m=context.getElementById(match[1]);return m&&m.parentNode?[m]:[];}},NAME:function(match,context){if(typeof context.getElementsByName!=="undefined"){var ret=[],results=context.getElementsByName(match[1]);for(var i=0,l=results.length;i<l;i++){if(results[i].getAttribute("name")===match[1]){ret.push(results[i]);}}
return ret.length===0?null:ret;}},TAG:function(match,context){if(typeof context.getElementsByTagName!=="undefined"){return context.getElementsByTagName(match[1]);}}},preFilter:{CLASS:function(match,curLoop,inplace,result,not,isXML){match=" "+match[1].replace(rBackslash,"")+" ";if(isXML){return match;}
for(var i=0,elem;(elem=curLoop[i])!=null;i++){if(elem){if(not^(elem.className&&(" "+elem.className+" ").replace(/[\t\n\r]/g," ").indexOf(match)>=0)){if(!inplace){result.push(elem);}}else if(inplace){curLoop[i]=false;}}}
return false;},ID:function(match){return match[1].replace(rBackslash,"");},TAG:function(match,curLoop){return match[1].replace(rBackslash,"").toLowerCase();},CHILD:function(match){if(match[1]==="nth"){if(!match[2]){Sizzle.error(match[0]);}
match[2]=match[2].replace(/^\+|\s*/g,'');var test=/(-?)(\d*)(?:n([+\-]?\d*))?/.exec(match[2]==="even"&&"2n"||match[2]==="odd"&&"2n+1"||!/\D/.test(match[2])&&"0n+"+match[2]||match[2]);match[2]=(test[1]+(test[2]||1))-0;match[3]=test[3]-0;}
else if(match[2]){Sizzle.error(match[0]);}
match[0]=done++;return match;},ATTR:function(match,curLoop,inplace,result,not,isXML){var name=match[1]=match[1].replace(rBackslash,"");if(!isXML&&Expr.attrMap[name]){match[1]=Expr.attrMap[name];}
match[4]=(match[4]||match[5]||"").replace(rBackslash,"");if(match[2]==="~="){match[4]=" "+match[4]+" ";}
return match;},PSEUDO:function(match,curLoop,inplace,result,not){if(match[1]==="not"){if((chunker.exec(match[3])||"").length>1||/^\w/.test(match[3])){match[3]=Sizzle(match[3],null,null,curLoop);}else{var ret=Sizzle.filter(match[3],curLoop,inplace,true^not);if(!inplace){result.push.apply(result,ret);}
return false;}}else if(Expr.match.POS.test(match[0])||Expr.match.CHILD.test(match[0])){return true;}
return match;},POS:function(match){match.unshift(true);return match;}},filters:{enabled:function(elem){return elem.disabled===false&&elem.type!=="hidden";},disabled:function(elem){return elem.disabled===true;},checked:function(elem){return elem.checked===true;},selected:function(elem){if(elem.parentNode){elem.parentNode.selectedIndex;}
return elem.selected===true;},parent:function(elem){return!!elem.firstChild;},empty:function(elem){return!elem.firstChild;},has:function(elem,i,match){return!!Sizzle(match[3],elem).length;},header:function(elem){return(/h\d/i).test(elem.nodeName);},text:function(elem){var attr=elem.getAttribute("type"),type=elem.type;return elem.nodeName.toLowerCase()==="input"&&"text"===type&&(attr===type||attr===null);},radio:function(elem){return elem.nodeName.toLowerCase()==="input"&&"radio"===elem.type;},checkbox:function(elem){return elem.nodeName.toLowerCase()==="input"&&"checkbox"===elem.type;},file:function(elem){return elem.nodeName.toLowerCase()==="input"&&"file"===elem.type;},password:function(elem){return elem.nodeName.toLowerCase()==="input"&&"password"===elem.type;},submit:function(elem){var name=elem.nodeName.toLowerCase();return(name==="input"||name==="button")&&"submit"===elem.type;},image:function(elem){return elem.nodeName.toLowerCase()==="input"&&"image"===elem.type;},reset:function(elem){return elem.nodeName.toLowerCase()==="input"&&"reset"===elem.type;},button:function(elem){var name=elem.nodeName.toLowerCase();return name==="input"&&"button"===elem.type||name==="button";},input:function(elem){return(/input|select|textarea|button/i).test(elem.nodeName);},focus:function(elem){return elem===elem.ownerDocument.activeElement;}},setFilters:{first:function(elem,i){return i===0;},last:function(elem,i,match,array){return i===array.length-1;},even:function(elem,i){return i%2===0;},odd:function(elem,i){return i%2===1;},lt:function(elem,i,match){return i<match[3]-0;},gt:function(elem,i,match){return i>match[3]-0;},nth:function(elem,i,match){return match[3]-0===i;},eq:function(elem,i,match){return match[3]-0===i;}},filter:{PSEUDO:function(elem,match,i,array){var name=match[1],filter=Expr.filters[name];if(filter){return filter(elem,i,match,array);}else if(name==="contains"){return(elem.textContent||elem.innerText||Sizzle.getText([elem])||"").indexOf(match[3])>=0;}else if(name==="not"){var not=match[3];for(var j=0,l=not.length;j<l;j++){if(not[j]===elem){return false;}}
return true;}else{Sizzle.error(name);}},CHILD:function(elem,match){var type=match[1],node=elem;switch(type){case"only":case"first":while((node=node.previousSibling)){if(node.nodeType===1){return false;}}
if(type==="first"){return true;}
node=elem;case"last":while((node=node.nextSibling)){if(node.nodeType===1){return false;}}
return true;case"nth":var first=match[2],last=match[3];if(first===1&&last===0){return true;}
var doneName=match[0],parent=elem.parentNode;if(parent&&(parent.sizcache!==doneName||!elem.nodeIndex)){var count=0;for(node=parent.firstChild;node;node=node.nextSibling){if(node.nodeType===1){node.nodeIndex=++count;}}
parent.sizcache=doneName;}
var diff=elem.nodeIndex-last;if(first===0){return diff===0;}else{return(diff%first===0&&diff/first>=0);}}},ID:function(elem,match){return elem.nodeType===1&&elem.getAttribute("id")===match;},TAG:function(elem,match){return(match==="*"&&elem.nodeType===1)||elem.nodeName.toLowerCase()===match;},CLASS:function(elem,match){return(" "+(elem.className||elem.getAttribute("class"))+" ").indexOf(match)>-1;},ATTR:function(elem,match){var name=match[1],result=Expr.attrHandle[name]?Expr.attrHandle[name](elem):elem[name]!=null?elem[name]:elem.getAttribute(name),value=result+"",type=match[2],check=match[4];return result==null?type==="!=":type==="="?value===check:type==="*="?value.indexOf(check)>=0:type==="~="?(" "+value+" ").indexOf(check)>=0:!check?value&&result!==false:type==="!="?value!==check:type==="^="?value.indexOf(check)===0:type==="$="?value.substr(value.length-check.length)===check:type==="|="?value===check||value.substr(0,check.length+1)===check+"-":false;},POS:function(elem,match,i,array){var name=match[2],filter=Expr.setFilters[name];if(filter){return filter(elem,i,match,array);}}}};var origPOS=Expr.match.POS,fescape=function(all,num){return"\\"+(num-0+1);};for(var type in Expr.match){Expr.match[type]=new RegExp(Expr.match[type].source+(/(?![^\[]*\])(?![^\(]*\))/.source));Expr.leftMatch[type]=new RegExp(/(^(?:.|\r|\n)*?)/.source+Expr.match[type].source.replace(/\\(\d+)/g,fescape));}
var makeArray=function(array,results){array=Array.prototype.slice.call(array,0);if(results){results.push.apply(results,array);return results;}
return array;};try{Array.prototype.slice.call(document.documentElement.childNodes,0)[0].nodeType;}catch(e){makeArray=function(array,results){var i=0,ret=results||[];if(toString.call(array)==="[object Array]"){Array.prototype.push.apply(ret,array);}else{if(typeof array.length==="number"){for(var l=array.length;i<l;i++){ret.push(array[i]);}}else{for(;array[i];i++){ret.push(array[i]);}}}
return ret;};}
var sortOrder,siblingCheck;if(document.documentElement.compareDocumentPosition){sortOrder=function(a,b){if(a===b){hasDuplicate=true;return 0;}
if(!a.compareDocumentPosition||!b.compareDocumentPosition){return a.compareDocumentPosition?-1:1;}
return a.compareDocumentPosition(b)&4?-1:1;};}else{sortOrder=function(a,b){var al,bl,ap=[],bp=[],aup=a.parentNode,bup=b.parentNode,cur=aup;if(a===b){hasDuplicate=true;return 0;}else if(aup===bup){return siblingCheck(a,b);}else if(!aup){return-1;}else if(!bup){return 1;}
while(cur){ap.unshift(cur);cur=cur.parentNode;}
cur=bup;while(cur){bp.unshift(cur);cur=cur.parentNode;}
al=ap.length;bl=bp.length;for(var i=0;i<al&&i<bl;i++){if(ap[i]!==bp[i]){return siblingCheck(ap[i],bp[i]);}}
return i===al?siblingCheck(a,bp[i],-1):siblingCheck(ap[i],b,1);};siblingCheck=function(a,b,ret){if(a===b){return ret;}
var cur=a.nextSibling;while(cur){if(cur===b){return-1;}
cur=cur.nextSibling;}
return 1;};}
Sizzle.getText=function(elems){var ret="",elem;for(var i=0;elems[i];i++){elem=elems[i];if(elem.nodeType===3||elem.nodeType===4){ret+=elem.nodeValue;}else if(elem.nodeType!==8){ret+=Sizzle.getText(elem.childNodes);}}
return ret;};(function(){var form=document.createElement("div"),id="script"+(new Date()).getTime(),root=document.documentElement;form.innerHTML="<a name='"+id+"'/>";root.insertBefore(form,root.firstChild);if(document.getElementById(id)){Expr.find.ID=function(match,context,isXML){if(typeof context.getElementById!=="undefined"&&!isXML){var m=context.getElementById(match[1]);return m?m.id===match[1]||typeof m.getAttributeNode!=="undefined"&&m.getAttributeNode("id").nodeValue===match[1]?[m]:undefined:[];}};Expr.filter.ID=function(elem,match){var node=typeof elem.getAttributeNode!=="undefined"&&elem.getAttributeNode("id");return elem.nodeType===1&&node&&node.nodeValue===match;};}
root.removeChild(form);root=form=null;})();(function(){var div=document.createElement("div");div.appendChild(document.createComment(""));if(div.getElementsByTagName("*").length>0){Expr.find.TAG=function(match,context){var results=context.getElementsByTagName(match[1]);if(match[1]==="*"){var tmp=[];for(var i=0;results[i];i++){if(results[i].nodeType===1){tmp.push(results[i]);}}
results=tmp;}
return results;};}
div.innerHTML="<a href='#'></a>";if(div.firstChild&&typeof div.firstChild.getAttribute!=="undefined"&&div.firstChild.getAttribute("href")!=="#"){Expr.attrHandle.href=function(elem){return elem.getAttribute("href",2);};}
div=null;})();if(document.querySelectorAll){(function(){var oldSizzle=Sizzle,div=document.createElement("div"),id="__sizzle__";div.innerHTML="<p class='TEST'></p>";if(div.querySelectorAll&&div.querySelectorAll(".TEST").length===0){return;}
Sizzle=function(query,context,extra,seed){context=context||document;if(!seed&&!Sizzle.isXML(context)){var match=/^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(query);if(match&&(context.nodeType===1||context.nodeType===9)){if(match[1]){return makeArray(context.getElementsByTagName(query),extra);}else if(match[2]&&Expr.find.CLASS&&context.getElementsByClassName){return makeArray(context.getElementsByClassName(match[2]),extra);}}
if(context.nodeType===9){if(query==="body"&&context.body){return makeArray([context.body],extra);}else if(match&&match[3]){var elem=context.getElementById(match[3]);if(elem&&elem.parentNode){if(elem.id===match[3]){return makeArray([elem],extra);}}else{return makeArray([],extra);}}
try{return makeArray(context.querySelectorAll(query),extra);}catch(qsaError){}}else if(context.nodeType===1&&context.nodeName.toLowerCase()!=="object"){var oldContext=context,old=context.getAttribute("id"),nid=old||id,hasParent=context.parentNode,relativeHierarchySelector=/^\s*[+~]/.test(query);if(!old){context.setAttribute("id",nid);}else{nid=nid.replace(/'/g,"\\$&");}
if(relativeHierarchySelector&&hasParent){context=context.parentNode;}
try{if(!relativeHierarchySelector||hasParent){return makeArray(context.querySelectorAll("[id='"+nid+"'] "+query),extra);}}catch(pseudoError){}finally{if(!old){oldContext.removeAttribute("id");}}}}
return oldSizzle(query,context,extra,seed);};for(var prop in oldSizzle){Sizzle[prop]=oldSizzle[prop];}
div=null;})();}
(function(){var html=document.documentElement,matches=html.matchesSelector||html.mozMatchesSelector||html.webkitMatchesSelector||html.msMatchesSelector;if(matches){var disconnectedMatch=!matches.call(document.createElement("div"),"div"),pseudoWorks=false;try{matches.call(document.documentElement,"[test!='']:sizzle");}catch(pseudoError){pseudoWorks=true;}
Sizzle.matchesSelector=function(node,expr){expr=expr.replace(/\=\s*([^'"\]]*)\s*\]/g,"='$1']");if(!Sizzle.isXML(node)){try{if(pseudoWorks||!Expr.match.PSEUDO.test(expr)&&!/!=/.test(expr)){var ret=matches.call(node,expr);if(ret||!disconnectedMatch||node.document&&node.document.nodeType!==11){return ret;}}}catch(e){}}
return Sizzle(expr,null,null,[node]).length>0;};}})();(function(){var div=document.createElement("div");div.innerHTML="<div class='test e'></div><div class='test'></div>";if(!div.getElementsByClassName||div.getElementsByClassName("e").length===0){return;}
div.lastChild.className="e";if(div.getElementsByClassName("e").length===1){return;}
Expr.order.splice(1,0,"CLASS");Expr.find.CLASS=function(match,context,isXML){if(typeof context.getElementsByClassName!=="undefined"&&!isXML){return context.getElementsByClassName(match[1]);}};div=null;})();function dirNodeCheck(dir,cur,doneName,checkSet,nodeCheck,isXML){for(var i=0,l=checkSet.length;i<l;i++){var elem=checkSet[i];if(elem){var match=false;elem=elem[dir];while(elem){if(elem.sizcache===doneName){match=checkSet[elem.sizset];break;}
if(elem.nodeType===1&&!isXML){elem.sizcache=doneName;elem.sizset=i;}
if(elem.nodeName.toLowerCase()===cur){match=elem;break;}
elem=elem[dir];}
checkSet[i]=match;}}}
function dirCheck(dir,cur,doneName,checkSet,nodeCheck,isXML){for(var i=0,l=checkSet.length;i<l;i++){var elem=checkSet[i];if(elem){var match=false;elem=elem[dir];while(elem){if(elem.sizcache===doneName){match=checkSet[elem.sizset];break;}
if(elem.nodeType===1){if(!isXML){elem.sizcache=doneName;elem.sizset=i;}
if(typeof cur!=="string"){if(elem===cur){match=true;break;}}else if(Sizzle.filter(cur,[elem]).length>0){match=elem;break;}}
elem=elem[dir];}
checkSet[i]=match;}}}
if(document.documentElement.contains){Sizzle.contains=function(a,b){return a!==b&&(a.contains?a.contains(b):true);};}else if(document.documentElement.compareDocumentPosition){Sizzle.contains=function(a,b){return!!(a.compareDocumentPosition(b)&16);};}else{Sizzle.contains=function(){return false;};}
Sizzle.isXML=function(elem){var documentElement=(elem?elem.ownerDocument||elem:0).documentElement;return documentElement?documentElement.nodeName!=="HTML":false;};var posProcess=function(selector,context){var match,tmpSet=[],later="",root=context.nodeType?[context]:context;while((match=Expr.match.PSEUDO.exec(selector))){later+=match[0];selector=selector.replace(Expr.match.PSEUDO,"");}
selector=Expr.relative[selector]?selector+"*":selector;for(var i=0,l=root.length;i<l;i++){Sizzle(selector,root[i],tmpSet);}
return Sizzle.filter(later,tmpSet);};window.Sizzle=Sizzle;})();

/**
 * JSONP sets up and allows you to execute a JSONP request by Oscar Godson ( https://github.com/oscargodson/jsonp )
 * @param {String} url  The URL you are requesting with the JSON data (you may include URL params)
 * @param {String} method  The method name for the callback function. Defaults to callback (for example, flickr's is "jsoncallback")
 * @param {Function} callback  The callback you want to execute as an anonymous function. The first parameter of the anonymous callback function is the JSON
 */

(function(window,undefined){var JSONP=function(url,method,callback){url=url||'';method=method||'';callback=callback||function(){};if(typeof method=='function'){callback=method;method='callback';}
var generatedFunction='jsonp'+Math.round(Math.random()*1000001)
window[generatedFunction]=function(json){callback(json);window[generatedFunction]=undefined;};if(url.indexOf('?')===-1){url=url+'?';}
else{url=url+'&';}
var jsonpScript=document.createElement('script');jsonpScript.setAttribute("src",url+method+'='+generatedFunction);document.getElementsByTagName("head")[0].appendChild(jsonpScript)}
window.JSONP=JSONP;})(window);

/**
 * http://www.json.org/license.html
 */
var JSON={copyright:'(c)2005 JSON.org',license:'http://www.crockford.com/JSON/license.html',stringify:function(v){var a=[];function e(s){a[a.length]=s;}
function g(x){var c,i,l,v;switch(typeof x){case'object':if(x){if(x instanceof Array){e('[');l=a.length;for(i=0;i<x.length;i+=1){v=x[i];if(typeof v!='undefined'&&typeof v!='function'){if(l<a.length){e(',');}
g(v);}}
e(']');return;}else if(typeof x.valueOf=='function'){e('{');l=a.length;for(i in x){v=x[i];if(typeof v!='undefined'&&typeof v!='function'&&(!v||typeof v!='object'||typeof v.valueOf=='function')){if(l<a.length){e(',');}
g(i);e(':');g(v);}}
return e('}');}}
e('null');return;case'number':e(isFinite(x)?+x:'null');return;case'string':l=x.length;e('"');for(i=0;i<l;i+=1){c=x.charAt(i);if(c>=' '){if(c=='\\'||c=='"'){e('\\');}
e(c);}else{switch(c){case'\b':e('\\b');break;case'\f':e('\\f');break;case'\n':e('\\n');break;case'\r':e('\\r');break;case'\t':e('\\t');break;default:c=c.charCodeAt();e('\\u00'+Math.floor(c/16).toString(16)+
(c%16).toString(16));}}}
e('"');return;case'boolean':e(String(x));return;default:e('null');return;}}
g(v);return a.join('');},parse:function(text){try{return!(/[^,:{}[]0-9.-+Eaeflnr-u \n\r\t]/.test(text.replace(/"(\\.|[^"\\])*"/g,'')))&&eval('('+text+')');}catch(e){return false;}}};

/**
 * Batchgeo JS API 0.1 Alpha
 * @requires Sizzle Because it uses it for making selecting elements easy for the developer/user
 * @param {String} sel The element you want to make a batchgeo object with.
 */

(function( window, undefined) {
  
  /**
   * Unlike other libraries, we have to do some hacking to make a "document.ready"
   * We're not looking for a document ready. We're looking to see when, in this order:
   * A. The Google AJAX library loads
   * B. The Google Maps 3 library loads
   * C. Batchgeo is ready
   * Once A-C are complete, we can execute the user's code.
   * You'll also notice we are writing to batchgeo now and then overwriting it after init() runs.
   * We do this because we need to make sure the keyword "batchgeo" is available instantly.
   * Last thing you should note is that exec is NOT a function, but an array and that .ready()
   * adds all the user's batchgeo.ready() callbacks to an array for later execution of each one.
   * If not, you could only do 1 .ready which is problematic in bigger production enviroments.
   */
  var exec = []
  ,   htmlAPI = true
  ,   batchgeo = {
        ready: function(callback){
          exec[exec.length] = callback;
        },
        htmlAPI: function(option){
          htmlAPI = option; //auto load the HTML API?
        }
    }
  
  var init = function(){
    JSONP('http://batchgeo.com/api/test/cacheCheck.php?url='+window.location.href,function(cache){
    
      var _markerArray = [];
      
      var _privateAPI = {
        /**
         * Simply returns the version number of this API
         */
        info: {
          version:'1.0.0'
        },
        /**
         * Creates a HTML element with the properties you want and appends it to wherever you want
         * @param {String} tagName  The tag you want to append (example: "iframe", "span", etc)
         * @param {Object} properties  The HTML properties/attributes you want to set (example: {width:'100', alt:'text'})
         * @param {String} appendTo  A CSS selector of where you want to append to.
         * @returns {Object}  "this" _privateAPI instance
         */
        createTag: function(tagName, properties, appendTo){
          var appendToID = Sizzle(appendTo)[0],
              newTag = document.createElement(tagName);
          
          for(x in properties){
            newTag.setAttribute(x,properties[x]);
          }
          
          appendToID.appendChild(newTag);
          
          return this;
        },
        /**
         * Appends whatever you want to wherever you want
         * @param {String}  what  What you want to append (can be HTML)
         * @param {String}  A CSS selector of where you want to append to
         * @returns {Object}  "this" _privateAPI instance
         */
        append:function(what,to){
          var appendToID = Sizzle(to)[0]
          appendToID.innerHTML = appendToID.innerHTML+what;
          return this;
        },
        /**
         * Merges two JS objects together. It overwrites existing keys that already exist
         * and it adds new keys if they didn't already exist. Useful for overwriting default settings
         * with user settings.
         * @param {Object} obj1  The first object to merge (matching keys will be overwritten by obj2)
         * @param {Object} obj2  The second object to merge
         * @returns {Object}  the merged object
         */
        mergeOptions: function(obj1,obj2){
          var obj3 = {};
          for(attrname in obj1) { obj3[attrname] = obj1[attrname]; }
          for(attrname in obj2) { obj3[attrname] = obj2[attrname]; }
          return obj3;
        },
        /**
         * As it's called, it checks if a string is JSON.
         * @param {String} str  the string you want to check if it is or isn't valid JSON
         * @returns {Boolean}  Returns true if it IS JSON and false if it is NOT JSON
         */
        isJSON: function(str){
          if(JSON.parse(str)) { return true; }
          else { return false; }
        },
        /**
         * Sends a JS object to the batchgeo server and saves it under the current URL
         * @param {Object} cacheObj  The object you want to cache for JSONP retrieval later.
         * @returns {Object}  "this" _privateAPI instance
         */
        saveCache: function(cacheObj){
          Sizzle('[name=batchgeo-post-address]')[0].value = JSON.stringify(cacheObj);
          document.getElementById('batchgeo-post-form').submit();
          return this;
        }
      }
      
      
      //Setup the POST form to send geocoding data to batchgeo
      _privateAPI
          //Creates the iframe to post
          .createTag('iframe',{ src:'', name:'batchgeo-post-iframe', 'id':'batchgeo-post-iframe', width:'0', height:'0', style:'display:none', frameBorder:'no' },'body')
          //Creates the form which targets the iframe above
          .createTag('form', { action:'http://batchgeo.com/api/test/post.php', method:'post', target:'batchgeo-post-iframe', 'id':'batchgeo-post-form' }, 'body')
            //Create hidden input to write the address' to
            .createTag('input', { type:'hidden', name:'batchgeo-post-address', 'id':'batchgeo-post-address'}, '#batchgeo-post-form');
      
      batchgeo = function(sel){
        
        var _defaultSettings = {
          width:'auto', //Set to some value to override the height of the map (default is 100% of the container, set with CSS, or here)
          height:'auto', //Set to some value to override the height of the map (default is 0, set with CSS, or here)
          animateMarkers:true, //Turns off the dropping animations
          markerImage:null, //Custom marker image "src"
          mapType:'roadmap', //what style of map you want, can be satellite, terrain, hyrid, or roadmap
          traffic:false, //If true, will show the traffic layer on top of the map
          bicycling:false, //If true will show the bicycling layer on top of the map,
          controls:true, //If you want to show the controls
          showLinkBack:true, //If you want to show the http://maps.google.com/?daddr=... link in the InfoWindows
          adsenseId:null //If you have an adsense account and you want to use your own account to make some revenue, add it here
        }
        
        
        var publicAPI = {
          source: function(src){
            //If the string was JSON, then parse it and turn it into a JS object
            if(_privateAPI.isJSON(src)){ src = JSON.parse(src); }
            
            if(typeof src == 'object'){
              this.src = {
                data: src,
                type: 'object',
                selector:null
              }
            }
            /**
             * Pure JSON is a string, so I need to check for this here!
             */
            else if(typeof src == 'string'){
              
              var htmlSource = Sizzle(src+' li')
              ,   tempSrcObj = [];
              
              for(x = 0;x < htmlSource.length;x++){
                tempSrcObj[x] = {};
                
                
                try {
                  //The .replace() removes all HTML and replaces with a space. innerText/textContent
                  //removes the HTML, but doesnt add a space and confuses Google Maps.
                  tempSrcObj[x].address = Sizzle('address',htmlSource[x])[0].innerHTML.replace(/<.+>/g,' ');
                }
                catch(e){
                  console.log('Error: No address found!');
                }
                
                //Try to grab a description, but if there is none found...
                try {
                  var d = htmlSource[x].innerHTML;
                  tempSrcObj[x].info = d;
                }
                catch(e) {
                  //...give out a warning
                  console.log('Warning: No description found.')
                }
              }
              
              
              this.src = {
                data: tempSrcObj,
                type: 'string',
                selector:htmlSource
              };
            }
            return this;
          },
          map: function(action,callback){
            action = action || 'create';
            callback = callback || function(){};
            
            var source = this.src
            ,   mapOptions = _defaultSettings;
            
            if(action == 'create'){
    
                var mapIt = function(tempMapObj){
                  
                  if(Sizzle(sel)[0].offsetHeight < 1){
                    console.log('Warning: No height set on map! It will not be visible.');
                  }
                  
                  var publisherId
                  ,   adCount = 2;
                  
                  if(mapOptions.adsenseId !== null){
                    adCount = 3;
                  }
                  
                  var randomId = Math.floor(Math.random()*adCount);
                  
                  if(randomId == 0){
                    publisherId = 'pub-4866411333549870'; //P
                  }
                  else if(randomId == 1){
                    publisherId = 'pub-4958411528779216'; //O
                  }
                  else {
                    publisherId = mapOptions.adsenseId;
                  }
                  
                  //Reverse the true/false because Google's param is backwards, "disableDefaulUI"
                  if(mapOptions.controls == true){
                    mapOptions.controls = false;
                  }
                  else if(mapOptions.controls == false){
                    mapOptions.controls = true;
                  }
                  
                  var mapSettings = {
                        center: new google.maps.LatLng(tempMapObj[0].LatLng.Ba,tempMapObj[0].LatLng.Ca),
                        mapTypeId: google.maps.MapTypeId[mapOptions.mapType.toUpperCase()],
                        disableDefaultUI: mapOptions.controls
                      }
                  ,   map = new google.maps.Map(Sizzle(sel)[0], mapSettings)
                  ,   infoWindow = new google.maps.InfoWindow()
                  ,   latLngBounds = new google.maps.LatLngBounds()
                  ,   adUnit
                  ,   tempMarker
                  ,   adUnitDiv = document.createElement('div')
                  ,   adUnitOptions = {
                        format: google.maps.adsense.AdFormat.VERTICAL_BANNER,
                        position: google.maps.ControlPosition.RIGHT_CENTER,
                        map: map,
                        visible: true,
                        publisherId: publisherId
                      };
                  
                  adUnitDiv.setAttribute("class","google-maps-adsense "+adUnitOptions.publisherId);
                  //adUnit = new google.maps.adsense.AdUnit(adUnitDiv, adUnitOptions);
                  
                   var closeInfoWindow = function() {
                     infoWindow.close();
                   };
                   
                   var openInfoWindow = function(marker,info) {
                    infoWindow.setContent(info);
                    infoWindow.open(map, marker);
                   }
                  
                  for(x=0;x < tempMapObj.length;x++){
                    var tempLatLng = new google.maps.LatLng(tempMapObj[x].LatLng.Ba,tempMapObj[x].LatLng.Ca);
                    
                    var toAnimate = false;
                    if(mapOptions.animateMarkers == true){
                      toAnimate = google.maps.Animation.DROP;
                    }
                    
                    var markerSettings = {
                      position:tempLatLng,
                      animation: toAnimate,
                      map:map
                    }
                    
                    if(_defaultSettings.markerImage !== null){
                      markerSettings.icon = _defaultSettings.markerImage;
                    }
                    
                    var tempMarker = new google.maps.Marker(markerSettings);
                    
                    latLngBounds.extend(tempLatLng);
                      
                    var attachListener = function(type, options) {
                      type = type || 'default';
                      options = options || {};
                      options.address = options.address || '';
                      options.info = options.info || '';
                      
                      //Set it here because the defaults.linkBackContent uses it inside it's value
                      var address = ''
                      ,   defaults = {
                            info:'',
                            selector:'',
                            marker:null,
                            linkBackContent:'<p class="link-back"><a href="http://maps.google.com/?daddr='+encodeURIComponent(options.address)+'">Get directions to here</a></p>'
                          }
                      ,   settings = _privateAPI.mergeOptions(defaults,options);
                      
                      if(mapOptions.showLinkBack == false){
                        settings.linkBackContent = '';
                      }
                      
                      if(type == 'dom'){
                        google.maps.event.addDomListener(settings.selector, 'click', function() {
                          openInfoWindow(settings.marker, settings.info+settings.linkBackContent);
                        });
                      }
                      else {
                        google.maps.event.addListener(settings.marker,'click',function(){
                          openInfoWindow(settings.marker, settings.info+settings.linkBackContent);
                        });
                      }
                      
                      return settings.marker;
                    }
                    
                    if(source.selector !== null){
                      attachListener('dom',{
                        selector:source.selector[x],
                        marker:tempMarker,
                        info:tempMapObj[x].InfoWindow,
                        address:tempMapObj[x].originalAddress
                      });
                    }
                    
                    _markerArray[x] = attachListener('default',{
                      marker:tempMarker,
                      info:tempMapObj[x].InfoWindow,
                      address:tempMapObj[x].originalAddress
                    });
                    
                  }
                  
                  map.fitBounds(latLngBounds);
                  
                  //Show traffic layer if they want it
                  if(mapOptions.traffic == true){
                    var trafficLayer = new google.maps.TrafficLayer();
                    trafficLayer.setMap(map); 
                  }
                  
                  if(mapOptions.bicycling == true){
                    var bikeLayer = new google.maps.BicyclingLayer();
                    bikeLayer.setMap(map);
                  }
                  
                  //The callback to access the DOM after this loads
                  //Not sure yet what to put here...
                  callback.call(this,tempMapObj);
                }
                
                var json = source.data
                ,   mappingObj = []
                ,   mappingObjCount = 0
                ,   createMapObj = function(){
                      new google.maps.Geocoder().geocode({address:json[mappingObjCount].address},function(jsonResults,resultStatus){
                        
                        //This checks if the results DIDNT come back as OK from Google
                        if(resultStatus !== 'OK'){
                          console.log('Error: '+resultStatus);
                          
                          if(
                             resultStatus == 'ZERO_RESULTS'   ||
                             resultStatus == 'ERROR'          ||
                             resultStatus == 'INVALID_REQUEST'||
                             resultStatus == 'REQUEST_DENIED' ||
                             resultStatus == 'UNKNOWN_ERROR'
                            ){
                            mappingObjCount++;
                            //Check if there is another address to check after the current one, if not...
                            if(json[mappingObjCount]){ createMapObj(); }
                            //...check if there is at least 1 item in the current "to be mapped" mappingObj, if not...
                            else if(mappingObj.length > 0){
                              mapIt(mappingObj);
                              _privateAPI.saveCache(mappingObj);
                            }
                            //...no map can be created.
                            else{ console.log('Error: no map was generated'); } 
                          }
                          //This error occurs when we send Google too many things to be geocoded too fast
                          //If this happens, just hit Google over and over fast (1/4 of a second) until it lets us
                          //geocode our markers. Notice that unlike everywhere else in geocode() callback, we do NOT
                          //increment mappingObjCount because we are constantly retrying the same marker.
                          else if(resultStatus == 'OVER_QUERY_LIMIT'){
                            setTimeout(function(){ createMapObj(); },250);
                          }
                        }
                        //...else, if it came back as more than nothing...
                        else{
                          //...save the info to a new mappingObj item
                          mappingObj[mappingObj.length] = {
                            'LatLng':jsonResults[0].geometry.location,
                            'InfoWindow':json[mappingObjCount].info,
                            'originalAddress':json[mappingObjCount].address
                          }
                          //If we're NOT on the last item
                          if(mappingObjCount < (json.length-1)){
                            mappingObjCount++;
                            createMapObj();
                          }
                          //Otherwise, if we are, then mapIt() now!
                          else{
                            mapIt(mappingObj);
                            _privateAPI.saveCache(mappingObj);
                          } 
                        }
                      });
                };
                if(cache !== undefined){
                  if(cache.error == "NO CACHES FOUND"){
                    createMapObj();
                  }
                  else{
                    var addressMatch = 0 //Set to 0 until there is a matching address
                    ,   infoMatch = 0 //Set to 0 until there is a matching infoWindow
                    ,   isModified = 0; //Set to 1 if addressMatch or infoMatch have no matches found
                    for(c = 0;c < cache.length;c++){
                      for(j = 0; j < json.length; j++){
                        if(cache[c].originalAddress == json[j].address){ //If there was a matching address...
                          addressMatch  = 1;
                        }

                        //Removes all spaces before checking for diffs in case of HTML formatting differences, but not data differences
                        if(cache[c].InfoWindow && json[j].info && cache[c].InfoWindow.replace(/([\s]+)/g,'') == json[j].info.replace(/([\s]+)/g,'')){ //If there was a matching infoWindow
                          infoMatch = 1;
                        }
                      }
                      //If there were NO matches, isModified becomes true, we then break from this loop to save time
                      //because we know something was modified, we don't need to keep looking.
                      if(addressMatch == 0 || infoMatch == 0){
                        isModified = 1;
                        break; 
                      }
                      //Else, reset these to 0 for the next time around
                      else{
                        addressMatch = 0;
                        infoMatch = 0;
                      }
                    }
                    //If no matches were ever found we create a mapping object with Google Maps
                    if(isModified == 1){ createMapObj(); }
                    //Otherwise, we can just "mapIt()" from the cached JSON from the JSONP request
                    else { mapIt(cache); }
                  }
                }
                else{
                  createMapObj();
                }
            }
            else if(action == 'remove'){
              Sizzle(sel)[0].style = '';
              Sizzle(sel)[0].innerHTML = '';
            }
            return this;
          },
          options: function(params){
            var defaults  = _defaultSettings
            ,   settings = _privateAPI.mergeOptions(defaults,params)
            
            /**
             * Function checks if param d is a string of an int, and then checks
             * if "d" has a % or px at the end, and if not, adds px to fix user error
             * @param string|int d  the string or int to check if it has % or px
             * @returns string  a string containing the given value plus px IF px or % didn't exist in the string before
             */
            var checkIfExtensionExists = function(d){
              if(typeof d !== 'string'){
                d = d.toString();
              }
              if(d.indexOf('%') < 0 && d.indexOf('px') < 0){
                return d+'px';
              }
              else{
                return d;
              }
            }
            
            //If the width is not set to auto force a width
            if(settings.width !== 'auto'){
              settings.width = checkIfExtensionExists(settings.width);
              Sizzle(sel)[0].style.width = settings.width;
            }
            
            //If the height is not set to auto, force a height
            if(settings.height !== 'auto'){
              settings.height = checkIfExtensionExists(settings.height);
              Sizzle(sel)[0].style.height = settings.height;
            }
            
            _defaultSettings = settings;
            return this;
          },
          version: function(){
            return _privateAPI.info.version;
          }
        };
        
        return publicAPI;  
      };
      if(htmlAPI){
        /**
         * The following code enables HTML based maps
         * To enable an HTML based map provide the following markup:
         * <div id="batchgeo-map-1"></div>
         * <ul id="batchgeo-data-1">
         *   <li>
         *     <p>Content that'll be in the bubble</p>
         *     <address>123 Sesame Street</address>
         *   </li>
         * </ul>
         *
         * It can be a ul, OR li
         * The batchgeo-map-1 and batchgeo-data-1 numbers match so they become linked
         * if you wanted to add another map youd do the same but change the 1s to 2s or any number as
         * long as the numbers match.
         */
        var htmlMaps = Sizzle('[id*=batchgeo-map-]')
        ,   htmlMapsData
        ,   htmlMappingObj = [];
        //For every batchgeo-map-N...
        for(x=0;x < htmlMaps.length;x++){
          
          //...find the matching batchgeo-data element.
          htmlMapsData = Sizzle('[id=batchgeo-data-'+htmlMaps[x].id.split('batchgeo-map-')[1]+'] li');
          
          //Makes it appear clickable
          for(y=0;y < htmlMapsData.length;y++){
            htmlMapsData[y].style.cursor = 'pointer';
          }
          
          //Converts the data-batchgeo-* attributes into actual options then puts them in the .options() method
          var attrs = htmlMaps[x].attributes
          ,   attrOptions = {};
          for(a=0;a < attrs.length;a++){
            if(attrs[a].name.indexOf('data-batchgeo-') > -1){
              var optionName = attrs[a].name.split('data-batchgeo-')[1];
              
              optionName = optionName.replace(/-([a-z])/g, function (m, w) {
                  return w.toUpperCase();
              });
              
              //NOTE: Boolean options can be set to true, 1, yes, or on for "true" and false, 0, no, or off for "false".
              if(attrs[a].nodeValue === "true" || attrs[a].nodeValue === "1"  || attrs[a].nodeValue === "yes" || attrs[a].nodeValue === 'on')  { attrOptions[optionName] = true; }
              else if(attrs[a].nodeValue === "false" || attrs[a].nodeValue === "0"  || attrs[a].nodeValue === "no" || attrs[a].nodeValue === 'off') { attrOptions[optionName] = false; }
              else if(attrs[a].nodeValue === "null")  { attrOptions[optionName] = null; }
              else{ attrOptions[optionName] = attrs[a].nodeValue; }
              
            }
          }
          
          //Build the HTML map
          batchgeo('#'+htmlMaps[x].id).options(attrOptions).source('[id=batchgeo-data-'+htmlMaps[x].id.split('batchgeo-map-')[1]+']').map();
        }
      }
      
      //overwrite the existing batchgeo now that everything is loaded
      window.batchgeo = batchgeo;
      
      //For every batchgeo.ready() we need to run them.
      for(z = 0; z < exec.length;z++){
        exec[z]();
      }
    }); // /JSONP
  }
  
  /**
   * Function loads a JS file.
   * @param {String} filename the link to the JS file you want to load
   * @param {Function} what you want to run after the JS file is loaded
   * @returns N/A just inserts a <script> into the <head>
   */
  function _loadScript(filename,callback){
    var fileref=document.createElement('script');
    fileref.setAttribute("type","text/javascript");
    if(fileref.onreadystatechange !== null){
      fileref.onload = callback;
    }
    else{
      fileref.onreadystatechange = function() {
        if (this.readyState == 'loaded') {
          callback();
        }
      }
    }
    fileref.setAttribute("src", filename);
    if (typeof fileref!="undefined"){
      document.getElementsByTagName("head")[0].appendChild(fileref)
    }
  }

  //Load the Google AJAX library
  _loadScript('http://www.google.com/jsapi?key=ABQIAAAAfcH8aCob3WT9rBt7NZY5XxQarLEhGbTB16c3m5GTcR1vWsaw3hQlvTEfWWjIA7eOnymMx3KX53i0GQ',function(){
    google.load('maps', '3.4',{'other_params':'libraries=adsense&sensor=false','callback':init});
  });
  
  window.batchgeo = batchgeo;
  
})(window);