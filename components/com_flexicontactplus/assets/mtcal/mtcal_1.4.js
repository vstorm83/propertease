var Calendar=new Class({Implements:Options,options:{blocked:[],classes:[],days:['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],direction:0,draggable:true,months:['January','February','March','April','May','June','July','August','September','October','November','December'],navigation:1,offset:0,onHideStart:Class.empty,onHideComplete:Class.empty,onShowStart:Class.empty,onShowComplete:Class.empty,pad:1,tweak:{x:0,y:0}},initialize:function(obj,options){if(!obj){return false;}
this.setOptions(options);var keys=['calendar','prev','next','month','year','today','invalid','valid','inactive','active','hover','hilite'];var values=keys.map(function(key,i){if(this.options.classes[i]){if(this.options.classes[i].length){key=this.options.classes[i];}}
return key;},this);this.classes=values.associate(keys);this.calendar=new Element('div',{'styles':{left:'-1000px',opacity:0,position:'absolute',top:'-1000px',zIndex:1000}}).addClass(this.classes.calendar).inject(document.body,'inside');if(window.ie6){this.iframe=new Element('iframe',{'styles':{left:'-1000px',position:'absolute',top:'-1000px',zIndex:999}}).inject(document.body,'inside');this.iframe.style.filter='progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0)';}
this.fx=new Fx.Tween(this.calendar,{onStart:function(){if(this.calendar.getStyle('opacity')==0){if(window.ie6){this.iframe.setStyle('display','block');}
this.calendar.setStyle('display','block');this.fireEvent('onShowStart',this.element);}
else{this.fireEvent('onHideStart',this.element);}}.bind(this),onComplete:function(){if(this.calendar.getStyle('opacity')==0){this.calendar.setStyle('display','none');if(window.ie6){this.iframe.setStyle('display','none');}
this.fireEvent('onHideComplete',this.element);}
else{this.fireEvent('onShowComplete',this.element);}}.bind(this)});if(window.Drag&&this.options.draggable){this.drag=new Drag.Move(this.calendar,{onDrag:function(){if(window.ie6){this.iframe.setStyles({left:this.calendar.style.left,top:this.calendar.style.top});}}.bind(this)});}
this.calendars=[];var id=0;var d=new Date();d.setDate(d.getDate()+this.options.direction.toInt());for(var i in obj){var cal={button:new Element('button',{'type':'button'}),el:$(i),els:[],id:id++,month:d.getMonth(),visible:false,year:d.getFullYear()};if(!this.element(i,obj[i],cal)){continue;}
cal.el.addClass(this.classes.calendar);cal.button.addClass(this.classes.calendar).addEvent('click',function(cal){this.toggle(cal);}.pass(cal,this)).inject(cal.el,'after');cal.val=this.read(cal);Object.append(cal,this.bounds(cal));Object.append(cal,this.values(cal));this.rebuild(cal);this.calendars.push(cal);}},blocked:function(cal){var blocked=[];var offset=new Date(cal.year,cal.month,1).getDay();var last=new Date(cal.year,cal.month+1,0).getDate();this.options.blocked.each(function(date){var values=date.split(' ');for(var i=0;i<=3;i++){if(!values[i]){values[i]=(i==3)?'':'*';}
values[i]=values[i].contains(',')?values[i].split(','):new Array(values[i]);var count=values[i].length-1;for(var j=count;j>=0;j--){if(values[i][j].contains('-')){var val=values[i][j].split('-');for(var k=val[0];k<=val[1];k++){if(!values[i].contains(k)){values[i].push(k+'');}}
values[i].splice(j,1);}}}
if(values[2].contains(cal.year+'')||values[2].contains('*')){if(values[1].contains(cal.month+1+'')||values[1].contains('*')){values[0].each(function(val){if(val>0){blocked.push(val.toInt());}});if(values[3]){for(var i=0;i<last;i++){var day=(i+offset)%7;if(values[3].contains(day+'')){blocked.push(i+1);}}}}}},this);return blocked;},bounds:function(cal){var start=new Date(1000,0,1);var end=new Date(2999,11,31);var date=new Date().getDate()+this.options.direction.toInt();if(this.options.direction>0){start=new Date();start.setDate(date+this.options.pad*cal.id);}
if(this.options.direction<0){end=new Date();end.setDate(date-this.options.pad*(this.calendars.length-cal.id-1));}
cal.els.each(function(el){if(el.get('tag')=='select'){if(el.format.test('(y|Y)')){var years=[];el.getChildren().each(function(option){var values=this.unformat(option.value,el.format);if(!years.contains(values[0])){years.push(values[0]);}},this);years.sort(this.sort);if(years[0]>start.getFullYear()){d=new Date(years[0],start.getMonth()+1,0);if(start.getDate()>d.getDate()){start.setDate(d.getDate());}
start.setYear(years[0]);}
if(years.getLast()<end.getFullYear()){d=new Date(years.getLast(),end.getMonth()+1,0);if(end.getDate()>d.getDate()){end.setDate(d.getDate());}
end.setYear(years.getLast());}}
if(el.format.test('(F|m|M|n)')){var months_start=[];var months_end=[];el.getChildren().each(function(option){var values=this.unformat(option.value,el.format);if(typeOf(values[0])!='number'||values[0]==years[0]){if(!months_start.contains(values[1])){months_start.push(values[1]);}}
if(typeOf(values[0])!='number'||values[0]==years.getLast()){if(!months_end.contains(values[1])){months_end.push(values[1]);}}},this);months_start.sort(this.sort);months_end.sort(this.sort);if(months_start[0]>start.getMonth()){d=new Date(start.getFullYear(),months_start[0]+1,0);if(start.getDate()>d.getDate()){start.setDate(d.getDate());}
start.setMonth(months_start[0]);}
if(months_end.getLast()<end.getMonth()){d=new Date(start.getFullYear(),months_end.getLast()+1,0);if(end.getDate()>d.getDate()){end.setDate(d.getDate());}
end.setMonth(months_end.getLast());}}}},this);return{'start':start,'end':end};},caption:function(cal){var navigation={prev:{'month':true,'year':true},next:{'month':true,'year':true}};if(cal.year==cal.start.getFullYear()){navigation.prev.year=false;if(cal.month==cal.start.getMonth()&&this.options.navigation==1){navigation.prev.month=false;}}
if(cal.year==cal.end.getFullYear()){navigation.next.year=false;if(cal.month==cal.end.getMonth()&&this.options.navigation==1){navigation.next.month=false;}}
if(typeOf(cal.months)=='array'){if(cal.months.length==1&&this.options.navigation==2){navigation.prev.month=navigation.next.month=false;}}
var caption=new Element('caption');var prev=new Element('a').addClass(this.classes.prev).appendText('\x3c');var next=new Element('a').addClass(this.classes.next).appendText('\x3e');if(this.options.navigation==2){var month=new Element('span').addClass(this.classes.month).inject(caption,'inside');if(navigation.prev.month){prev.clone().addEvent('click',function(cal){this.navigate(cal,'m',-1);}.pass(cal,this)).inject(month,'inside');}
month.adopt(new Element('span').appendText(this.options.months[cal.month]));if(navigation.next.month){next.clone().addEvent('click',function(cal){this.navigate(cal,'m',1);}.pass(cal,this)).inject(month,'inside');}
var year=new Element('span').addClass(this.classes.year).inject(caption,'inside');if(navigation.prev.year){prev.clone().addEvent('click',function(cal){this.navigate(cal,'y',-1);}.pass(cal,this)).inject(year,'inside');}
year.adopt(new Element('span').appendText(cal.year));if(navigation.next.year){next.clone().addEvent('click',function(cal){this.navigate(cal,'y',1);}.pass(cal,this)).inject(year,'inside');}}
else{if(navigation.prev.month&&this.options.navigation){prev.clone().addEvent('click',function(cal){this.navigate(cal,'m',-1);}.pass(cal,this)).inject(caption,'inside');}
caption.adopt(new Element('span').addClass(this.classes.month).appendText(this.options.months[cal.month]));caption.adopt(new Element('span').addClass(this.classes.year).appendText(cal.year));if(navigation.next.month&&this.options.navigation){next.clone().addEvent('click',function(cal){this.navigate(cal,'m',1);}.pass(cal,this)).inject(caption,'inside');}}
return caption;},changed:function(cal){cal.val=this.read(cal);Object.append(cal,this.values(cal));this.rebuild(cal);if(!cal.val){return;}
if(cal.val.getDate()<cal.days[0]){cal.val.setDate(cal.days[0]);}
if(cal.val.getDate()>cal.days.getLast()){cal.val.setDate(cal.days.getLast());}
cal.els.each(function(el){el.value=this.format(cal.val,el.format);},this);this.check(cal);this.calendars.each(function(kal){if(kal.visible){this.display(kal);}},this);},check:function(cal){this.calendars.each(function(kal,i){if(kal.val){var change=false;if(i<cal.id){var bound=new Date(Date.parse(cal.val));bound.setDate(bound.getDate()-(this.options.pad*(cal.id-i)));if(bound<kal.val){change=true;}}
if(i>cal.id){var bound=new Date(Date.parse(cal.val));bound.setDate(bound.getDate()+(this.options.pad*(i-cal.id)));if(bound>kal.val){change=true;}}
if(change){if(kal.start>bound){bound=kal.start;}
if(kal.end<bound){bound=kal.end;}
kal.month=bound.getMonth();kal.year=bound.getFullYear();Object.append(kal,this.values(kal));kal.val=kal.days.contains(bound.getDate())?bound:null;this.write(kal);if(kal.visible){this.display(kal);}}}
else{kal.month=cal.month;kal.year=cal.year;}},this);},clicked:function(td,day,cal){cal.val=(this.value(cal)==day)?null:new Date(cal.year,cal.month,day);this.write(cal);if(!cal.val){cal.val=this.read(cal);}
if(cal.val){this.check(cal);this.toggle(cal);}
else{td.addClass(this.classes.valid);td.removeClass(this.classes.active);}},display:function(cal){this.calendar.empty();this.calendar.className=this.classes.calendar+' '+this.options.months[cal.month].toLowerCase();var div=new Element('div').inject(this.calendar,'inside');var table=new Element('table').inject(div,'inside').adopt(this.caption(cal));var thead=new Element('thead').inject(table,'inside');var tr=new Element('tr').inject(thead,'inside');for(var i=0;i<=6;i++){var th=this.options.days[(i+this.options.offset)%7];tr.adopt(new Element('th',{'title':th}).appendText(th.substr(0,1)));}
var tbody=new Element('tbody').inject(table,'inside');var tr=new Element('tr').inject(tbody,'inside');var d=new Date(cal.year,cal.month,1);var offset=((d.getDay()-this.options.offset)+7)%7;var last=new Date(cal.year,cal.month+1,0).getDate();var prev=new Date(cal.year,cal.month,0).getDate();var active=this.value(cal);var valid=cal.days;var inactive=[];var hilited=[];this.calendars.each(function(kal,i){if(kal!=cal&&kal.val){if(cal.year==kal.val.getFullYear()&&cal.month==kal.val.getMonth()){inactive.push(kal.val.getDate());}
if(cal.val){for(var day=1;day<=last;day++){d.setDate(day);if((i<cal.id&&d>kal.val&&d<cal.val)||(i>cal.id&&d>cal.val&&d<kal.val)){if(!hilited.contains(day)){hilited.push(day);}}}}}},this);var d=new Date();var today=new Date(d.getFullYear(),d.getMonth(),d.getDate()).getTime();for(var i=1;i<43;i++){if((i-1)%7==0){tr=new Element('tr').inject(tbody,'inside');}
var td=new Element('td').inject(tr,'inside');var day=i-offset;var date=new Date(cal.year,cal.month,day);var cls='';if(day===active){cls=this.classes.active;}
else if(inactive.contains(day)){cls=this.classes.inactive;}
else if(valid.contains(day)){cls=this.classes.valid;}
else if(day>=1&&day<=last){cls=this.classes.invalid;}
if(date.getTime()==today){cls=cls+' '+this.classes.today;}
if(hilited.contains(day)){cls=cls+' '+this.classes.hilite;}
td.addClass(cls);if(valid.contains(day)){td.setProperty('title',this.format(date,'D M jS Y'));td.addEvents({'click':function(td,day,cal){this.clicked(td,day,cal);}.pass([td,day,cal],this),'mouseover':function(td,cls){td.addClass(cls);}.pass([td,this.classes.hover]),'mouseout':function(td,cls){td.removeClass(cls);}.pass([td,this.classes.hover])});}
if(day<1){day=prev+day;}
else if(day>last){day=day-last;}
td.appendText(day);}},element:function(el,f,cal){if(typeOf(f)=='object'){for(var i in f){if(!this.element(i,f[i],cal)){return false;}}
return true;}
el=$(el);if(!el){return false;}
el.format=f;if(el.get('tag')=='select'){el.addEvent('change',function(cal){this.changed(cal);}.pass(cal,this));}
else{el.readOnly=true;el.addEvent('focus',function(cal){this.toggle(cal);}.pass(cal,this));}
cal.els.push(el);return true;},format:function(date,format){var str='';if(date){var j=date.getDate();var w=date.getDay();var l=this.options.days[w];var n=date.getMonth()+1;var f=this.options.months[n-1];var y=date.getFullYear()+'';for(var i=0,len=format.length;i<len;i++){var cha=format.charAt(i);switch(cha){case'y':y=y.substr(2);case'Y':str+=y;break;case'm':if(n<10){n='0'+n;}
case'n':str+=n;break;case'M':f=f.substr(0,3);case'F':str+=f;break;case'd':if(j<10){j='0'+j;}
case'j':str+=j;break;case'D':l=l.substr(0,3);case'l':str+=l;break;case'N':w+=1;case'w':str+=w;break;case'S':if(j%10==1&&j!='11'){str+='st';}
else if(j%10==2&&j!='12'){str+='nd';}
else if(j%10==3&&j!='13'){str+='rd';}
else{str+='th';}
break;default:str+=cha;}}}
return str;},navigate:function(cal,type,n){switch(type){case'm':if(typeOf(cal.months)=='array'){var i=cal.months.indexOf(cal.month)+n;if(i<0||i==cal.months.length){if(this.options.navigation==1){this.navigate(cal,'y',n);}
i=(i<0)?cal.months.length-1:0;}
cal.month=cal.months[i];}
else{var i=cal.month+n;if(i<0||i==12){if(this.options.navigation==1){this.navigate(cal,'y',n);}
i=(i<0)?11:0;}
cal.month=i;}
break;case'y':if(typeOf(cal.years)=='array'){var i=cal.years.indexOf(cal.year)+n;cal.year=cal.years[i];}
else{cal.year+=n;}
break;}
Object.append(cal,this.values(cal));if(typeOf(cal.months)=='array'){var i=cal.months.indexOf(cal.month);if(i<0){cal.month=cal.months[0];}}
this.display(cal);},read:function(cal){var arr=[null,null,null];cal.els.each(function(el){var values=this.unformat(el.value,el.format);values.each(function(val,i){if(typeOf(val)=='number'){arr[i]=val;}});},this);if(typeOf(arr[0])=='number'){cal.year=arr[0];}
if(typeOf(arr[1])=='number'){cal.month=arr[1];}
var val=null;if(arr.every(function(i){return typeOf(i)=='number';})){var last=new Date(arr[0],arr[1]+1,0).getDate();if(arr[2]>last){arr[2]=last;}
val=new Date(arr[0],arr[1],arr[2]);}
return(cal.val==val)?null:val;},rebuild:function(cal){cal.els.each(function(el){if(el.get('tag')=='select'&&el.format.test('^(d|j)$')){var d=this.value(cal);if(!d){d=el.value.toInt();}
el.empty();cal.days.each(function(day){var option=new Element('option',{'selected':(d==day),'value':((el.format=='d'&&day<10)?'0'+day:day)}).appendText(day).inject(el,'inside');},this);}},this);},sort:function(a,b){return a-b;},toggle:function(cal){document.removeEvents('mousedown');if(cal.visible){cal.visible=false;cal.button.removeClass(this.classes.active);this.fx.start('opacity',1,0);}
else{document.addEvent('mousedown',function(e){var el=e.target;var stop=false;while(el!=document.body&&el.nodeType==1){if(el==this.calendar){stop=true;}
this.calendars.each(function(kal){if(kal.button==el||kal.els.contains(el)){stop=true;}});if(stop){e.stop();return false;}
else{el=el.parentNode;}}
this.toggle(cal);}.bind(this));this.calendars.each(function(kal){if(kal==cal){kal.visible=true;kal.button.addClass(this.classes.active);}
else{kal.visible=false;kal.button.removeClass(this.classes.active);}},this);var size=window.getScrollSize();var coord=cal.button.getCoordinates();var x=coord.right+this.options.tweak.x;var y=coord.top+this.options.tweak.y;if(!this.calendar.coord){this.calendar.coord=this.calendar.getCoordinates();}
if(x+this.calendar.coord.width>size.x){x-=(x+this.calendar.coord.width-size.x);}
if(y+this.calendar.coord.height>size.y){y-=(y+this.calendar.coord.height-size.y);}
this.calendar.setStyles({left:x+'px',top:y+'px'});if(window.ie6){this.iframe.setStyles({height:this.calendar.coord.height+'px',left:x+'px',top:y+'px',width:this.calendar.coord.width+'px'});}
this.display(cal);this.fx.start('opacity',0,1);}},unformat:function(val,f){f=f.escapeRegExp();var re={d:'([0-9]{2})',j:'([0-9]{1,2})',D:'('+this.options.days.map(function(day){return day.substr(0,3);}).join('|')+')',l:'('+this.options.days.join('|')+')',S:'(st|nd|rd|th)',F:'('+this.options.months.join('|')+')',m:'([0-9]{2})',M:'('+this.options.months.map(function(month){return month.substr(0,3);}).join('|')+')',n:'([0-9]{1,2})',Y:'([0-9]{4})',y:'([0-9]{2})'}
var arr=[];var g='';for(var i=0;i<f.length;i++){var c=f.charAt(i);if(re[c]){arr.push(c);g+=re[c];}
else{g+=c;}}
var matches=val.match('^'+g+'$');var dates=new Array(3);if(matches){matches=matches.slice(1);arr.each(function(c,i){i=matches[i];switch(c){case'y':i='20'+i;case'Y':dates[0]=i.toInt();break;case'F':i=i.substr(0,3);case'M':i=this.options.months.map(function(month){return month.substr(0,3);}).indexOf(i)+1;case'm':case'n':dates[1]=i.toInt()-1;break;case'd':case'j':dates[2]=i.toInt();break;}},this);}
return dates;},value:function(cal){var day=null;if(cal.val){if(cal.year==cal.val.getFullYear()&&cal.month==cal.val.getMonth()){day=cal.val.getDate();}}
return day;},values:function(cal){var years,months,days;cal.els.each(function(el){if(el.get('tag')=='select'){if(el.format.test('(y|Y)')){years=[];el.getChildren().each(function(option){var values=this.unformat(option.value,el.format);if(!years.contains(values[0])){years.push(values[0]);}},this);years.sort(this.sort);}
if(el.format.test('(F|m|M|n)')){months=[];el.getChildren().each(function(option){var values=this.unformat(option.value,el.format);if(typeOf(values[0])!='number'||values[0]==cal.year){if(!months.contains(values[1])){months.push(values[1]);}}},this);months.sort(this.sort);}
if(el.format.test('(d|j)')&&!el.format.test('^(d|j)$')){days=[];el.getChildren().each(function(option){var values=this.unformat(option.value,el.format);if(values[0]==cal.year&&values[1]==cal.month){if(!days.contains(values[2])){days.push(values[2]);}}},this);}}},this);var first=1;var last=new Date(cal.year,cal.month+1,0).getDate();if(cal.year==cal.start.getFullYear()){if(months==null&&this.options.navigation==2){months=[];for(var i=0;i<12;i++){if(i>=cal.start.getMonth()){months.push(i);}}}
if(cal.month==cal.start.getMonth()){first=cal.start.getDate();}}
if(cal.year==cal.end.getFullYear()){if(months==null&&this.options.navigation==2){months=[];for(var i=0;i<12;i++){if(i<=cal.end.getMonth()){months.push(i);}}}
if(cal.month==cal.end.getMonth()){last=cal.end.getDate();}}
var blocked=this.blocked(cal);if(typeOf(days)=='array'){days=days.filter(function(day){if(day>=first&&day<=last&&!blocked.contains(day)){return day;}});}
else{days=[];for(var i=first;i<=last;i++){if(!blocked.contains(i)){days.push(i);}}}
days.sort(this.sort);return{'days':days,'months':months,'years':years};},write:function(cal){this.rebuild(cal);cal.els.each(function(el){el.value=this.format(cal.val,el.format);},this);}});Calendar.implement(new Events,new Options);