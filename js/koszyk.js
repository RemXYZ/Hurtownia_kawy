var PUP = getEl('.pop_up_product');

function openPUP (el) {

PUP.style.display = "block";

for (let key in prices_record) {
	if (el.value == key) {
		let p_r = prices_record[key];
		getEl('.order_name').innerHTML = p_r.name;
		getEl('.order_price').value = p_r.p;
		getEl('.order_gram').value = p_r.g;
		getEl('.order_img').setAttribute('src', p_r.img);
		getEl('.order_id').value = key;
		post_id.id = key;
	}
}

}

// openPUP(getEl('.PUP-but2')[1]);

function insertId() {
	getEl(".order_id").value = post_id.id;
}
