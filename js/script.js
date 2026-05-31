function tambahCart(){
    alert("Produk berhasil ditambahkan ke keranjang!");
}

function registrasi(){

    let password = document.querySelectorAll("input[type=password]")[0].value;
    let konfirmasi = document.querySelectorAll("input[type=password]")[1].value;

    if(password.length < 6){
        alert("Password minimal 6 karakter");
        return;
    }

    if(password !== konfirmasi){
        alert("Password tidak cocok");
        return;
    }

    alert("Registrasi berhasil!");
}