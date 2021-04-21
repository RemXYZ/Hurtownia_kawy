const inp_prc = getEl(".goods_weight");

window.onload = function() {
for (let el of inp_prc) {
	el.addEventListener("keyup",calculate_price);
}
}

const prices = {};



function calculate_price () {
let price_node = this.find_node("goods_price");
if (typeof price_node == "undefined") {return false;}
let id = this.classList[1],
price = price_node.children[0],
gram = price_node.children[1],
gram_int = 0,
price_int = 0;
var regex = /[0-9]+/gi;
if (typeof prices[id] == "undefined") {
	if (regex.test(price.innerHTML)) {
		price_int = Number(price.innerHTML.match(regex).join(''));
	}
	if (regex.test(gram.innerHTML)) {
		gram_int = Number(gram.innerHTML.match(regex).join(''))
	}
	prices[id] = {
		'p':price_int,
		'g':gram_int
	}
}

gram.innerHTML = "/"+this.value+"g";
let new_price = (prices[id].p*Number(this.value))/prices[id].g;
price.innerHTML = new_price.toFixed(2)+"zł";


//INFO DLA PHP
let price_inp_n = this.find_node("price_inp_n"),
gram_inp_n = this.find_node("gram_inp_n");

price_inp_n.value = new_price.toFixed(2);
gram_inp_n.value = this.value;

prices_record[id].p = new_price.toFixed(2);
prices_record[id].g = this.value;

}

function submit_f (el) {

	let p_info = prices_record[el.value],
	scr_top = document.documentElement.scrollTop;
	w_H = window.innerHeight;
	getEl(".pop_up_product").style.top = scr_top/2 + (w_H/2)+"px";

	// POZNIEJ DLA AJAX
	// getEl(".pop_up_product").style.display = "block";
	// getEl(".pup_name").innerHTML = p_info.name;
	// getEl(".pup_price").value = p_info.p+"zł";
	// getEl(".pup_gram").value = p_info.g+"g";

	//INFO DLA PHP
let img_inp_n = el.find_node("img_inp_n"),
name_inp_n = el.find_node("name_inp_n"),
price_inp_n = el.find_node("price_inp_n"),
gram_inp_n = el.find_node("gram_inp_n");

img_inp_n.value = p_info.img;
name_inp_n.value = p_info.name;
price_inp_n.value = p_info.p+"zł";
gram_inp_n.value = p_info.g+"g";

	el.form.submit();
}

function close_PUP() {
	getEl(".pop_up_product").style.display = "none";
}