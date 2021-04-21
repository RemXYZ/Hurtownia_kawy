//!!!
//The whole script was written by an individual Rem Karablin except for sources that are highlighted as //sourse [url] ... //sourceEnd
//!!!
//GET ELEMENT 
function getEl (mix) {
	let result = document.querySelectorAll(mix);
	if (result.length < 2) {
		return result[0];
	}
	return result;
}

//CSS OF ELEMENT
Object.prototype.CSSinfo =  function() {
return window.getComputedStyle(this,null);
}

Object.prototype.find_node = function (mix) {
let el_par = this,
stop = true;
while(stop) {
	el_par = el_par.parentNode;
	if (!el_par.classList.contains(mix) || el_par.id != mix) {
		let el_chd = el_par.children;
		for (let chld of el_chd) {
			if (chld.classList.contains(mix) || chld.id == mix) {
				return chld;
				stop = false;
			}
		}
	}else {
		return el_par;
		break;
	}
	if (el_par == document.body) {
		stop = false;
	}
}
}
