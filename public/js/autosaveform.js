/*
* Auto Save Form script
* Created: Aug 8th, 2011 by DynamicDrive.com. This notice must stay intact for usage 
* Author: Dynamic Drive at http://www.dynamicdrive.com/
* Visit http://www.dynamicdrive.com/ for full source code
*/

function autosaveform(setting){
	if (!autosaveform.domstorage || !window.JSON) //if browser doesn't support dom storage or JSON
		return
	var $=jQuery
	var defaults={ //default setting values
		includefields:['text', 'textarea', 'checkbox', 'radio', 'select'],
		savingmsg:'Saving...',
		pause:1000,
		onsave:function(f){}
	}
	var setting=$.extend({}, defaults, setting)
	this.setting=setting
	this.onsave=setting.onsave
	var fieldskeywords=setting.includefields.join(" ")
	setting.includefields=$.map(setting.includefields, function(a){ //mold fieldskeywords into array of jQuery selector strings
		return (a=="select" || a=="textarea")? a : "input[type='"+a+"']"
	})
	var thissession=this
	jQuery(function($){ //on document.ready
		var $f=$('form#'+setting.formid).css({position:'relative'})
		thissession.f=$f.get(0)
		var targetfields=$(thissession.f.elements).filter(setting.includefields.join(',')).get() //filter out form fields that should be auto saved
		thissession.targetfields=targetfields
		thissession.loadfields() //load saved value into these fields (if any)
		var formevts='keydown cut paste'+ (/(checkbox)|(radio)|(select)/i.test(fieldskeywords)? ' change' : '') //Event keywords for form that will trigger saving of form field values
		$f.bind(formevts, function(e){
			var target=e.target.type.replace(/-\w+/i, "") || e.target.tagName //get target user is currently interacting with (using keywords found in fieldskeywords)
			if (fieldskeywords.indexOf(target.toLowerCase())!=-1)
				thissession.activatesave()
		})
		$f.submit(function(){
			thissession.savefields("clear") //clear saved form values when form is submitted
		})
		if (setting.savingmsg)
			thissession.$statusdiv=$('<div class="savestatus" style="visibility:hidden">' + setting.savingmsg + '</div>').prependTo($f)
	})
}

autosaveform.prototype={

	getel:function(id){
		return (document.getElementById(id) || this.f.elements[id])
	},

	getelname:function(el){
		return el.id || el.name
	},

	activatesave:function(){
		var thissession=this
		clearTimeout(this.savetimer)
		this.savetimer=setTimeout(function(){thissession.savefields()}, this.setting.pause)
	},

	savefields:function(action){ 
		var savedvalue={}
		var processedboxes=[]
		if (action!="clear"){
			for (var i=0, targets=this.targetfields, len=targets.length; i<len; i++){ //loop thru all fields in form that script should check whether to save their value
				var elname=this.getelname(targets[i])
				if (elname){ //if element carries an ID or NAME attribute
					if (/text/i.test(targets[i].type) && targets[i].value!=""){ //for textarea and input type=text elements
							savedvalue[elname]=targets[i].value
					}
					else if (/(checkbox)|(radio)/i.test(targets[i].type)){ //for checkbox/radios
						if (jQuery.inArray(elname, processedboxes)==-1){ //if this group of checkboxes (ones sharing the same name) haven't been processed yet
							for (var c=0, cgroup=this.f.elements[elname], clength=cgroup.length; c<clength; c++){ //loop through checkboxes/ radio buttons within group
								if (cgroup[c].checked){
									savedvalue[elname]=(typeof savedvalue[elname]=="undefined")? [c] : savedvalue[elname].concat([c])
								}
							} //end for loop
							processedboxes.push(targets[i].name)
						}
					}
					else if (targets[i].type.indexOf("select")!=-1){ //for select menus
						for (var o=0, opts=targets[i].options, olength=opts.length; o<olength; o++){
							if (opts[o].selected)
								savedvalue[elname]=(typeof savedvalue[elname]=="undefined")? [o] : savedvalue[elname].concat([o])
						}
					}
				} //end if elname
			} //end for loop
			if (this.$statusdiv)
				this.$statusdiv.css({opacity:0, visibility:'visible'}) //show "saving form" notice temporarily
					.animate({opacity:1},200).delay(400).animate({opacity:0},200)
			try{ //call onsave event handler
				this.onsave(this.f, savedvalue)
			}
			catch(e){
				throw new Error("An error has occured inside your onsave() function:\n" + e.message)
			}
		} //end if action
		autosaveform._storage(this.f, "save", JSON.stringify(savedvalue))
	},

	loadfields:function(){
		try{
			var loadedvalue=JSON.parse(autosaveform._storage(this.f, "load"))
		}catch(e){
			var loadedvalue={}
		}
		for (var elname in loadedvalue){ //loop thru each field name property inside loadedvalue
			if (loadedvalue.hasOwnProperty(elname)){
				var el=this.getel(elname)
				if (el){ //if element with this name/id actually exists on the form (form may have been changed)
					if (/(text)/.test(el.type) && typeof loadedvalue[elname]=="string"){ //if this is a input type="text" or textarea element
						el.value=loadedvalue[elname]
					}
					else if (typeof loadedvalue[elname]=="object" && el.type==undefined && /(checkbox)|(radio)/i.test(el[0].type)){ //checkbox/radios
						for (var c=0, cgroup=this.f.elements[elname], clength=loadedvalue[elname].length; c<clength; c++){ //loop through saved checkboxes/ radio buttons array of numbers
							var checkedindex=loadedvalue[elname][c]
							if (cgroup[checkedindex]){ //if checkbox/radio button at this index exists on the page
								cgroup[checkedindex].checked=true
							}
						} //end for loop				
					}
					else if (/select/i.test(el.type)){ //select menu
						for (var o=0, opts=el.options, olength=loadedvalue[elname].length; o<olength; o++){ //loop through saved select array of numbers
							var selectedindex=loadedvalue[elname][o]
							opts[selectedindex].selected=true
						}
					}
				}
			} //end if
		} //end for
	}

}


autosaveform.domstorage=window.localStorage || (window.globalStorage? globalStorage[location.hostname] : null)
//Create cookie prefix using the page's URL. Mold from "http://mysite.com/sub/file.htm" for example to "sub/file.htm"
autosaveform.cookieprefix=location.href.replace(new RegExp("("+location.host+"/)|("+location.protocol+"//)", "g"), "")

autosaveform._storage=function(form, action, data){
	var domstorage=autosaveform.domstorage
	if (action=="load"){
		return domstorage[this.cookieprefix+"_"+form.id]
	}
	else if (action=="save"){
		domstorage[this.cookieprefix+"_"+form.id]=data
	}
}
