'use strict';function
qs(selector,context){return(context||document).querySelector(selector);}function
qsl(selector,context){const
els=qsa(selector,context);return els[els.length-1];}function
qsa(selector,context){return(context||document).querySelectorAll(selector);}function
partial(fn){const
args=Array.apply(null,arguments).slice(1);return function(){return fn.apply(this,args);};}function
partialArg(fn){const
args=Array.apply(null,arguments);return function(arg){args[0]=arg;return fn.apply(this,args);};}function
mixin(target,source){for(const
key
in
source){target[key]=source[key];}}function
alterClass(el,className,enable){if(el){el.classList[enable?'add':'remove'](className);}}function
toggle(id){const
el=qs('#'+id);el&&el.classList.toggle('hidden');return false;}function
cookie(assign,days){const
date=new
Date();date.setDate(date.getDate()+days);document.cookie=assign+'; expires='+date;}function
verifyVersion(current,url,token){cookie('adminer_version=0',1);const
iframe=document.createElement('iframe');iframe.src='https://www.adminer.org/version/?current='+current;iframe.frameBorder=0;iframe.marginHeight=0;iframe.scrolling='no';iframe.style.width='7ex';iframe.style.height='1.25em';iframe.style.display='none';addEventListener('message',event=>{if(event.origin=='https://www.adminer.org'){const
match=/version=(.+)/.exec(event.data);if(match){cookie('adminer_version='+match[1],1);ajax(url+'script=version',()=>{},event.data+'&token='+token);}}},false);qs('#version').appendChild(iframe);}function
selectValue(select){if(!select.selectedIndex){return select.value;}const
selected=select.options[select.selectedIndex];return((selected.attributes.value||{}).specified?selected.value:selected.text);}function
isTag(el,tag){const
re=new
RegExp('^('+tag+')$','i');return el&&re.test(el.tagName);}function
parentTag(el,tag){while(el&&!isTag(el,tag)){el=el.parentNode;}return el;}function
trCheck(el){const
tr=parentTag(el,'tr');alterClass(tr,'checked',el.checked);if(el.form&&el.form['all']&&el.form['all'].onclick){el.form['all'].onclick();}}function
selectCount(id,count){setHtml(id,(count===''?'':'('+(count+'').replace(/\B(?=(\d{3})+$)/g,thousandsSeparator)+')'));const
el=qs('#'+id);if(el){for(const
input
of
qsa('input',el.parentNode.parentNode)){if(input.type=='submit'){input.disabled=(count=='0');}}}}function
formCheck(name){for(const
elem
of
this.form.elements){if(name.test(elem.name)){elem.checked=this.checked;trCheck(elem);}}}function
tableCheck(){for(const
input
of
qsa('table.checkable td:first-child input')){trCheck(input);}}function
formUncheck(id){const
el=qs('#'+id);el.checked=false;trCheck(el);}function
formChecked(input,name){let
checked=0;for(const
el
of
input.form.elements){if(name.test(el.name)&&el.checked){checked++;}}return checked;}function
tableClick(event,click){const
td=parentTag(event.target,'td');let
text;if(td&&(text=td.getAttribute('data-text'))){if(selectClick.call(td,event,+text,td.getAttribute('data-warning'))){return;}}click=(click||!window.getSelection||getSelection().isCollapsed);let
el=event.target;while(!isTag(el,'tr')){if(isTag(el,'table|a|input|textarea')){if(el.type!='checkbox'){return;}checkboxClick.call(el,event);click=false;}el=el.parentNode;if(!el){return;}}el=el.firstChild.firstChild;if(click){el.checked=!el.checked;el.onclick&&el.onclick();}if(el.name=='check[]'){el.form['all'].checked=false;formUncheck('all-page');}if(/^(tables|views)\[\]$/.test(el.name)){formUncheck('check-all');}trCheck(el);}let
lastChecked;function
checkboxClick(event){if(!this.name){return;}if(event.shiftKey&&(!lastChecked||lastChecked.name==this.name)){const
checked=(lastChecked?lastChecked.checked:true);let
checking=!lastChecked;for(const
input
of
qsa('input',parentTag(this,'table'))){if(input.name===this.name){if(checking){input.checked=checked;trCheck(input);}if(input===this||input===lastChecked){if(checking){break;}checking=true;}}}}else{lastChecked=this;}}function
setHtml(id,html){const
el=qs('[id="'+id.replace(/[\\"]/g,'\\$&')+'"]');if(el){if(html==null){el.parentNode.innerHTML='';}else{el.innerHTML=html;}}}function
nodePosition(el){let
pos=0;while((el=el.previousSibling)){pos++;}return pos;}function
pageClick(href,page){if(!isNaN(page)&&page){location.href=href+(page!=1?'&page='+(page-1):'');}}function
menuOver(event){const
a=event.target;if(isTag(a,'a|span')&&a.offsetLeft+a.offsetWidth>a.parentNode.offsetWidth-15){this.style.overflow='visible';}}function
menuOut(){this.style.overflow='hidden';}function
selectAddRow(){const
field=this;const
row=cloneNode(field.parentNode);field.onchange=selectFieldChange;field.onchange();for(const
select
of
qsa('select',row)){select.name=select.name.replace(/[a-z]\[\d+/,'$&1');select.selectedIndex=0;}for(const
input
of
qsa('input',row)){input.name=input.name.replace(/[a-z]\[\d+/,'$&1');input.className='';if(input.type=='checkbox'){input.checked=false;}else{input.value='';}}field.parentNode.parentNode.appendChild(row);}function
selectSearchKeydown(event){if(event.keyCode==13||event.keyCode==10){this.onsearch=()=>{};}}function
selectSearchSearch(){if(!this.value){this.parentNode.firstChild.selectedIndex=0;}}function
columnMouse(className){for(const
span
of
qsa('span',this)){if(/column/.test(span.className)){span.className='column'+(className||'');}}}function
selectSearch(name){let
el=qs('#fieldset-search');el.className='';const
divs=qsa('div',el);let
i,div;for(i=0;i<divs.length;i++){div=divs[i];el=qs('[name$="[col]"]',div);if(el&&selectValue(el)==name){break;}}if(i==divs.length){div.firstChild.value=name;div.firstChild.onchange();}qs('[name$="[val]"]',div).focus();return false;}function
isCtrl(event){return(event.ctrlKey||event.metaKey)&&!event.altKey;}function
bodyKeydown(event,button){eventStop(event);let
target=event.target;if(target.jushTextarea){target=target.jushTextarea;}if(isCtrl(event)&&(event.keyCode==13||event.keyCode==10)&&isTag(target,'select|textarea|input')){target.blur();if(target.form[button]){target.form[button].click();}else{target.form.dispatchEvent(new
Event('submit',{bubbles:true}));target.form.submit();}target.focus();return false;}return true;}function
bodyClick(event){const
target=event.target;if((isCtrl(event)||event.shiftKey)&&target.type=='submit'&&isTag(target,'input')){target.form.target='_blank';setTimeout(()=>{target.form.target='';},0);}}function
editingKeydown(event){if((event.keyCode==40||event.keyCode==38)&&isCtrl(event)){const
target=event.target;const
sibling=(event.keyCode==40?'nextSibling':'previousSibling');let
el=target.parentNode.parentNode[sibling];if(el&&(isTag(el,'tr')||(el=el[sibling]))&&isTag(el,'tr')&&(el=el.childNodes[nodePosition(target.parentNode)])&&(el=el.childNodes[nodePosition(target)])){el.focus();}return false;}if(event.shiftKey&&!bodyKeydown(event,'insert')){return false;}return true;}function
functionChange(){const
input=this.form[this.name.replace(/^function/,'fields')];if(input){if(selectValue(this)){if(input.origType===undefined){input.origType=input.type;input.origMaxLength=input.getAttribute('data-maxlength');}input.removeAttribute('data-maxlength');input.type='text';}else
if(input.origType){input.type=input.origType;if(input.origMaxLength>=0){input.setAttribute('data-maxlength',input.origMaxLength);}}oninput({target:input});}helpClose();}function
skipOriginal(first){const
fnSelect=qs('select',this.previousSibling);if(fnSelect.selectedIndex<first){fnSelect.selectedIndex=first;}}function
fieldChange(){const
row=cloneNode(parentTag(this,'tr'));for(const
input
of
qsa('input',row)){input.value='';}parentTag(this,'table').appendChild(row);this.oninput=()=>{};}function
ajax(url,callback,data,message){const
request=new
XMLHttpRequest();if(request){const
ajaxStatus=qs('#ajaxstatus');if(message){ajaxStatus.innerHTML='<div class="message">'+message+'</div>';}alterClass(ajaxStatus,'hidden',!message);request.open((data?'POST':'GET'),url);if(data){request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}request.setRequestHeader('X-Requested-With','XMLHttpRequest');request.onreadystatechange=()=>{if(request.readyState==4){if(/^2/.test(request.status)){callback(request);}else
if(message!==null){ajaxStatus.innerHTML=(request.status?request.responseText:'<div class="error">'+offlineMessage+'</div>');alterClass(ajaxStatus,'hidden');}}};request.send(data);}return request;}function
ajaxSetHtml(url){return!ajax(url,request=>{const
data=JSON.parse(request.responseText);for(const
key
in
data){setHtml(key,data[key]);}});}let
editChanged;let
adminerHighlighter=els=>{};function
ajaxForm(form,message,button){let
data=[];for(const
el
of
form.elements){if(el.name&&!el.disabled){if(/^file$/i.test(el.type)&&el.value){return false;}if(!/^(checkbox|radio|submit|file)$/i.test(el.type)||el.checked||el==button){data.push(encodeURIComponent(el.name)+'='+encodeURIComponent(isTag(el,'select')?selectValue(el):el.value));}}}data=data.join('&');let
url=form.action;if(!/post/i.test(form.method)){url=url.replace(/\?.*/,'')+'?'+data;data='';}return ajax(url,request=>{const
ajaxstatus=qs('#ajaxstatus');setHtml('ajaxstatus',request.responseText);if(qs('.message',ajaxstatus)){editChanged=null;}adminerHighlighter(qsa('code',ajaxstatus));messagesPrint(ajaxstatus);},data,message);}function
selectClick(event,text,warning){const
td=this;const
target=event.target;if(!isCtrl(event)||isTag(td.firstChild,'input|textarea')||isTag(target,'a')){return;}if(warning){alert(warning);return true;}const
original=td.innerHTML;text=text||/\n/.test(original);const
input=document.createElement(text?'textarea':'input');input.onkeydown=event=>{if(event.keyCode==27&&!event.shiftKey&&!event.altKey&&!isCtrl(event)){inputBlur.apply(input);td.innerHTML=original;}};const
pos=getSelection().anchorOffset;let
value=(td.firstChild&&td.firstChild.alt)||td.textContent;const
tdStyle=window.getComputedStyle(td,null);input.style.width=Math.max(td.clientWidth-parseFloat(tdStyle.paddingLeft)-parseFloat(tdStyle.paddingRight),(text?200:20))+'px';if(text){let
rows=1;value.replace(/\n/g,()=>{rows++;});input.rows=rows;}if(qsa('i',td).length){value='';}td.innerHTML='';td.appendChild(input);setupSubmitHighlight(td);input.focus();if(text==2){return ajax(location.href+'&'+encodeURIComponent(td.id)+'=',request=>{if(request.responseText){input.value=request.responseText;input.name=td.id;}});}input.value=value;input.name=td.id;input.selectionStart=pos;input.selectionEnd=pos;return true;}function
selectLoadMore(limit,loading){const
a=this;const
title=a.innerHTML;const
href=a.href;a.innerHTML=loading;if(href){a.removeAttribute('href');return!ajax(href,request=>{const
tbody=document.createElement('tbody');tbody.innerHTML=request.responseText;qs('#table').appendChild(tbody);if(tbody.children.length<limit){a.parentNode.removeChild(a);}else{a.href=href.replace(/\d+$/,page=>+page+1);a.innerHTML=title;}});}}function
eventStop(event){event.stopPropagation();}function
setupSubmitHighlight(parent){for(const
input
of
qsa('input, select, textarea',parent)){setupSubmitHighlightInput(input);}}function
setupSubmitHighlightInput(input){if(!/submit|button|image|file/.test(input.type)){addEvent(input,'focus',inputFocus);addEvent(input,'blur',inputBlur);}}function
inputFocus(){alterClass(findDefaultSubmit(this),'default',true);}function
inputBlur(){alterClass(findDefaultSubmit(this),'default');}function
findDefaultSubmit(el){if(el.jushTextarea){el=el.jushTextarea;}if(!el.form){return null;}for(const
input
of
qsa('input',el.form)){if(input.type=='submit'&&!input.style.zIndex){return input;}}}function
addEvent(el,action,handler){el.addEventListener(action,handler,false);}function
cloneNode(el){const
el2=el.cloneNode(true);const
selector='input, select';const
origEls=qsa(selector,el);const
cloneEls=qsa(selector,el2);for(let
i=0;i<origEls.length;i++){const
origEl=origEls[i];for(const
key
in
origEl){if(/^on/.test(key)&&origEl[key]){cloneEls[i][key]=origEl[key];}}}setupSubmitHighlight(el2);return el2;}oninput=event=>{const
target=event.target;const
maxLength=target.getAttribute('data-maxlength');alterClass(target,'maxlength',target.value&&maxLength!=null&&target.value.length>maxLength);};addEvent(document,'click',event=>{if(!qs('#foot').contains(event.target)){alterClass(qs('#foot'),'foot',true);}});let
autocompleter;function
syntaxHighlighting(version,vendor){addEventListener('DOMContentLoaded',()=>{if(window.jush){jush.create_links='target="_blank" rel="noreferrer noopener"';if(version){for(let
key
in
jush.urls){let
obj=jush.urls;if(typeof
obj[key]!='string'){obj=obj[key];key=0;if(vendor=='maria'){for(let
i=1;i<obj.length;i++){obj[i]=obj[i].replace('.html','/').replace('-type-syntax','-data-types').replace(/numeric-(data-types)/,'$1-$&').replace(/replication-options-(master|binary-log)\//,'replication-and-binary-log-system-variables/').replace('server-options/','server-system-variables/').replace('innodb-parameters/','innodb-system-variables/').replace(/#(statvar|sysvar|option_mysqld)_(.*)/,'#$2').replace(/#sysvar_(.*)/,'#$1');}}}obj[key]=(vendor=='maria'?obj[key].replace('dev.mysql.com/doc/mysql','mariadb.com/kb'):obj[key]).replace('/doc/mysql','/doc/refman/'+version);if(vendor!='cockroach'){obj[key]=obj[key].replace('/docs/current','/docs/'+version);}}}if(window.jushLinks){jush.custom_links=jushLinks;}jush.highlight_tag('code',0);adminerHighlighter=els=>jush.highlight_tag(els,0);for(const
tag
of
qsa('textarea')){if(/(^|\s)jush-/.test(tag.className)){const
pre=jush.textarea(tag,autocompleter);if(pre){setupSubmitHighlightInput(pre);tag.onchange=()=>{pre.textContent=tag.value;pre.oninput();};}}}}});}function
formField(form,name){for(let
i=0;i<form.length;i++){if(form[i].name==name){return form[i];}}}function
typePassword(el,disable){try{el.type=(disable?'text':'password');}catch(e){}}function
messagesPrint(parent){for(const
el
of
qsa('.toggle',parent)){el.onclick=partial(toggle,el.getAttribute('href').substr(1));}}function
loginDriver(driver){const
trs=parentTag(driver,'table').rows;const
disabled=/sqlite/.test(selectValue(driver));alterClass(trs[1],'hidden',disabled);trs[1].getElementsByTagName('input')[0].disabled=disabled;}let
dbCtrl;const
dbPrevious={};function
dbMouseDown(event){if(event.target.tagName=="OPTION"){return;}dbCtrl=isCtrl(event);if(dbPrevious[this.name]==undefined){dbPrevious[this.name]=this.value;}}function
dbChange(){if(dbCtrl){this.form.target='_blank';}this.form.submit();this.form.target='';if(dbCtrl&&dbPrevious[this.name]!=undefined){this.value=dbPrevious[this.name];dbPrevious[this.name]=undefined;}}function
selectFieldChange(){const
form=this.form;const
ok=(()=>{for(const
input
of
qsa('input',form)){if(input.value&&/^fulltext/.test(input.name)){return true;}}let
ok=form.limit.value;let
group=false;const
columns={};for(const
select
of
qsa('select',form)){const
col=selectValue(select);let
match=/^(where.+)col]/.exec(select.name);if(match){const
op=selectValue(form[match[1]+'op]']);const
val=form[match[1]+'val]'].value;if(col
in
indexColumns&&(!/LIKE|REGEXP/.test(op)||(op=='LIKE'&&val.charAt(0)!='%'))){return true;}else
if(col||val){ok=false;}}if((match=/^(columns.+)fun]/.exec(select.name))){if(/^(avg|count|count distinct|group_concat|max|min|sum)$/.test(col)){group=true;}const
val=selectValue(form[match[1]+'col]']);if(val){columns[col&&col!='count'?'':val]=1;}}if(col&&/^order/.test(select.name)){if(!(col
in
indexColumns)){ok=false;}break;}}if(group){for(const
col
in
columns){if(!(col
in
indexColumns)){ok=false;}}}return ok;})();setHtml('noindex',(ok?'':'!'));}let
added='.',rowCount;function
delimiterEqual(val,a,b){return(val==a+'_'+b||val==a+b||val==a+b.charAt(0).toUpperCase()+b.substr(1));}function
idfEscape(s){return s.replace(/`/,'``');}function
editFields(){for(const
el
of
qsa('[name$="[field]"]')){el.oninput=function(){editingNameChange.call(this);if(!this.defaultValue){editingAddRow.call(this);}};}for(const
el
of
qsa('[name$="[length]"]')){mixin(el,{onfocus:editingLengthFocus,oninput:editingLengthChange});}for(const
el
of
qsa('[name$="[type]"]')){mixin(el,{onfocus:function(){lastType=selectValue(this);},onchange:editingTypeChange,onmouseover:function(event){helpMouseover.call(this,event,event.target.value,1);},onmouseout:helpMouseout});}}function
editingClick(event){let
el=parentTag(event.target,'button');if(el){const
name=el.name;if(/^add\[/.test(name)){editingAddRow.call(el,1);}else
if(/^up\[/.test(name)){editingMoveRow.call(el,1);}else
if(/^down\[/.test(name)){editingMoveRow.call(el);}else
if(/^drop_col\[/.test(name)){editingRemoveRow.call(el,'fields$1[field]');}return false;}el=event.target;if(!isTag(el,'input')){el=parentTag(el,'label');el=el&&qs('input',el);}if(el){const
name=el.name;if(name=='auto_increment_col'){const
field=el.form['fields['+el.value+'][field]'];if(!field.value){field.value='id';field.oninput();}}}}function
editingInput(event){const
el=event.target;if(/\[default]$/.test(el.name)){el.previousElementSibling.checked=true;el.previousElementSibling.selectedIndex=Math.max(el.previousElementSibling.selectedIndex,1);}}function
editingNameChange(){const
name=this.name.substr(0,this.name.length-7);const
type=formField(this.form,name+'[type]');const
opts=type.options;let
candidate;const
val=this.value;for(let
i=opts.length;i--;){const
match=/(.+)`(.+)/.exec(opts[i].value);if(!match){if(candidate&&i==opts.length-2&&val==opts[candidate].value.replace(/.+`/,'')&&name=='fields[1]'){return;}break;}const
base=match[1];const
column=match[2];for(const
table
of[base,base.replace(/s$/,''),base.replace(/es$/,'')]){if(val==column||val==table||delimiterEqual(val,table,column)||delimiterEqual(val,column,table)){if(candidate){return;}candidate=i;break;}}}if(candidate){type.selectedIndex=candidate;type.onchange();}}function
editingAddRow(focus){const
match=/(\d+)(\.\d+)?/.exec(this.name);const
x=match[0]+(match[2]?added.substr(match[2].length):added)+'1';const
row=parentTag(this,'tr');const
row2=cloneNode(row);let
tags=qsa('select, input, button',row);let
tags2=qsa('select, input, button',row2);for(let
i=0;i<tags.length;i++){tags2[i].name=tags[i].name.replace(/[0-9.]+/,x);tags2[i].selectedIndex=(/\[(generated)/.test(tags[i].name)?0:tags[i].selectedIndex);}tags=qsa('input',row);tags2=qsa('input',row2);const
input=tags2[0];for(let
i=0;i<tags.length;i++){if(tags[i].name=='auto_increment_col'){tags2[i].value=x;tags2[i].checked=false;}if(/\[(orig|field|comment|default)/.test(tags[i].name)){tags2[i].value='';}if(/\[(generated)/.test(tags[i].name)){tags2[i].checked=false;}}tags[0].oninput=editingNameChange;row.parentNode.insertBefore(row2,row.nextSibling);if(focus){input.oninput=editingNameChange;input.focus();}added+='0';rowCount++;return false;}function
editingRemoveRow(name){const
field=formField(this.form,this.name.replace(/[^[]+(.+)/,name));field.parentNode.removeChild(field);parentTag(this,'tr').style.display='none';return false;}function
editingMoveRow(up){const
row=parentTag(this,'tr');if(!('nextElementSibling'in
row)){return true;}row.parentNode.insertBefore(row,up?row.previousElementSibling:row.nextElementSibling?row.nextElementSibling.nextElementSibling:row.parentNode.firstChild);return false;}let
lastType='';function
editingTypeChange(){const
type=this;const
name=type.name.substr(0,type.name.length-6);const
text=selectValue(type);for(const
el
of
type.form.elements){if(el.name==name+'[length]'){if(!((/(char|binary)$/.test(lastType)&&/(char|binary)$/.test(text))||(/(enum|set)$/.test(lastType)&&/(enum|set)$/.test(text)))){el.value='';}el.oninput.apply(el);}if(lastType=='timestamp'&&el.name==name+'[generated]'&&/timestamp/i.test(formField(type.form,name+'[default]').value)){el.checked=false;el.selectedIndex=0;}if(el.name==name+'[collation]'){alterClass(el,'hidden',!/(char|text|enum|set)$/.test(text));}if(el.name==name+'[unsigned]'){alterClass(el,'hidden',!/(^|[^o])int(?!er)|numeric|real|float|double|decimal|money/.test(text));}if(el.name==name+'[on_update]'){alterClass(el,'hidden',!/timestamp|datetime/.test(text));}if(el.name==name+'[on_delete]'){alterClass(el,'hidden',!/`/.test(text));}}helpClose();}function
editingLengthChange(){alterClass(this,'required',!this.value.length&&/var(char|binary)$/.test(selectValue(this.parentNode.previousSibling.firstChild)));}function
editingLengthFocus(){const
td=this.parentNode;if(/(enum|set)$/.test(selectValue(td.previousSibling.firstChild))){const
edit=qs('#enum-edit');edit.value=enumValues(this.value);td.appendChild(edit);this.style.display='none';edit.style.display='inline';edit.focus();}}function
enumValues(s){const
re=/(^|,)\s*'(([^\\']|\\.|'')*)'\s*/g;const
result=[];let
offset=0;let
match;while((match=re.exec(s))){if(offset!=match.index){break;}result.push(match[2].replace(/'(')|\\(.)/g,'$1$2'));offset+=match[0].length;}return(offset==s.length?result.join('\n'):s);}function
editingLengthBlur(){const
field=this.parentNode.firstChild;const
val=this.value;field.value=(/^'[^\n]+'$/.test(val)?val:val&&"'"+val.replace(/\n+$/,'').replace(/'/g,"''").replace(/\\/g,'\\\\').replace(/\n/g,"','")+"'");field.style.display='inline';this.style.display='none';}function
columnShow(checked,column){for(const
tr
of
qsa('tr',qs('#edit-fields'))){alterClass(qsa('td',tr)[column],'hidden',!checked);}}function
indexOptionsShow(checked){for(const
option
of
qsa('.idxopts')){alterClass(option,'hidden',!checked);}}function
partitionByChange(){const
partitionTable=/RANGE|LIST/.test(selectValue(this));alterClass(this.form['partitions'],'hidden',partitionTable||!this.selectedIndex);alterClass(qs('#partition-table'),'hidden',!partitionTable);helpClose();}function
partitionNameChange(){const
row=cloneNode(parentTag(this,'tr'));row.firstChild.firstChild.value='';parentTag(this,'table').appendChild(row);this.oninput=()=>{};}function
editingCommentsClick(el,focus){const
comment=el.form['Comment'];columnShow(el.checked,6);alterClass(comment,'hidden',!el.checked);if(focus&&el.checked){comment.focus();}}function
dumpClick(event){let
el=parentTag(event.target,'label');if(el){el=qs('input',el);const
match=/(.+)\[]$/.exec(el.name);if(match){checkboxClick.call(el,event);formUncheck('check-'+match[1]);}}}function
foreignAddRow(){const
row=cloneNode(parentTag(this,'tr'));this.onchange=()=>{};for(const
select
of
qsa('select',row)){select.name=select.name.replace(/\d+]/,'1$&');select.selectedIndex=0;}parentTag(this,'table').appendChild(row);}function
indexesAddRow(){const
row=cloneNode(parentTag(this,'tr'));this.onchange=()=>{};for(const
select
of
qsa('select',row)){select.name=select.name.replace(/indexes\[\d+/,'$&1');select.selectedIndex=0;}for(const
input
of
qsa('input',row)){input.name=input.name.replace(/indexes\[\d+/,'$&1');input.value='';}parentTag(this,'table').appendChild(row);}function
indexesChangeColumn(prefix){const
names=[];for(const
tag
in{'select':1,'input':1}){for(const
column
of
qsa(tag,parentTag(this,'td'))){if(/\[columns]/.test(column.name)){const
value=selectValue(column);if(value){names.push(value);}}}}this.form[this.name.replace(/].*/,'][name]')].value=prefix+names.join('_');}function
indexesAddColumn(prefix){const
field=this;const
select=field.form[field.name.replace(/].*/,'][type]')];if(!select.selectedIndex){while(selectValue(select)!="INDEX"&&select.selectedIndex<select.options.length){select.selectedIndex++;}select.onchange();}const
column=cloneNode(field.parentNode);for(const
select
of
qsa('select',column)){select.name=select.name.replace(/]\[\d+/,'$&1');select.selectedIndex=0;}field.onchange=partial(indexesChangeColumn,prefix);for(const
input
of
qsa('input',column)){input.name=input.name.replace(/]\[\d+/,'$&1');if(input.type!='checkbox'){input.value='';}}parentTag(field,'td').appendChild(column);field.onchange();}function
sqlSubmit(form,root){if(encodeURIComponent(form['query'].value).length<500){form.action=root+'&sql='+encodeURIComponent(form['query'].value)+(form['limit'].value?'&limit='+
+form['limit'].value:'')+(form['error_stops'].checked?'&error_stops=1':'')+(form['only_errors'].checked?'&only_errors=1':'');}}function
triggerChange(tableRe,table,form){const
formEvent=selectValue(form['Event']);if(tableRe.test(form['Trigger'].value)){form['Trigger'].value=table+'_'+(selectValue(form['Timing']).charAt(0)+formEvent.charAt(0)).toLowerCase();}alterClass(form['Of'],'hidden',!/ OF/.test(formEvent));}let
that,x,y;function
schemaMousedown(event){if((event.which||event.button)==1){that=this;x=event.clientX-this.offsetLeft;y=event.clientY-this.offsetTop;}}function
schemaMousemove(event){if(that!==undefined){const
left=(event.clientX-x)/em;const
top=(event.clientY-y)/em;const
lineSet={};for(const
div
of
qsa('div',that)){if(div.classList.contains('references')){const
div2=qs('[id="'+(/^refs/.test(div.id)?'refd':'refs')+div.id.substr(4)+'"]');const
ref=(tablePos[div.title]||[div2.parentNode.offsetTop/em,0]);let
left1=-1;const
id=div.id.replace(/^ref.(.+)-.+/,'$1');if(div.parentNode!=div2.parentNode){left1=Math.min(0,ref[1]-left)-1;div.style.left=left1+'em';div.querySelector('div').style.width=-left1+'em';const
left2=Math.min(0,left-ref[1])-1;div2.style.left=left2+'em';div2.querySelector('div').style.width=-left2+'em';}if(!lineSet[id]){const
line=qs('[id="'+div.id.replace(/^....(.+)-.+$/,'refl$1')+'"]');const
top1=top+div.offsetTop/em;let
top2=top+div2.offsetTop/em;if(div.parentNode!=div2.parentNode){top2+=ref[0]-top;line.querySelector('div').style.height=Math.abs(top1-top2)+'em';}line.style.left=(left+left1)+'em';line.style.top=Math.min(top1,top2)+'em';lineSet[id]=true;}}}that.style.left=left+'em';that.style.top=top+'em';}}function
schemaMouseup(event,db){if(that!==undefined){tablePos[that.firstChild.firstChild.firstChild.data]=[(event.clientY-y)/em,(event.clientX-x)/em];that=undefined;let
s='';for(const
key
in
tablePos){s+='_'+key+':'+Math.round(tablePos[key][0])+'x'+Math.round(tablePos[key][1]);}s=encodeURIComponent(s.substr(1));const
link=qs('#schema-link');link.href=link.href.replace(/[^=]+$/,'')+s;cookie('adminer_schema-'+db+'='+s,30);}}let
helpOpen,helpIgnore;function
helpMouseover(event,text,side){const
target=event.target;if(!text){helpClose();}else
if(window.jush&&(!helpIgnore||this!=target)){helpOpen=1;const
help=qs('#help');help.innerHTML=text;jush.highlight_tag([help]);alterClass(help,'hidden');const
rect=target.getBoundingClientRect();const
body=document.documentElement;help.style.top=(body.scrollTop+rect.top-(side?(help.offsetHeight-target.offsetHeight)/2:help.offsetHeight))+'px';help.style.left=(body.scrollLeft+rect.left-(side?help.offsetWidth:(help.offsetWidth-target.offsetWidth)/2))+'px';}}function
helpMouseout(event){helpOpen=0;helpIgnore=(this!=event.target);setTimeout(()=>{if(!helpOpen){helpClose();}},200);}function
helpClose(){alterClass(qs('#help'),'hidden',true);}