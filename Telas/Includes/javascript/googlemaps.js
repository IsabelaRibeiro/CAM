var mapa;
var ponto;
var pontos = new Array();
var pontosP = new Array();
var linha;
var poligono;
var poligonoP;
var zoom;
var dadosMapa;


google.maps.LatLng.prototype.kmTo = function(a) {
    var e = Math, ra = e.PI / 180;
    var b = this.lat() * ra,
        c = a.lat() * ra,
        d = b - c;
    var g = this.lng() * ra - a.lng() * ra;
    var f = 2 * e.asin(e.sqrt(e.pow(e.sin(d / 2), 2) + e.cos(b) * e.cos(c) * e.pow(e.sin(g / 2), 2)));
    return f * 6378.137;
}
google.maps.Polyline.prototype.inKm = function(n) {
    var a = this.getPath(n),
        len = a.getLength(),
        dist = 0;
    for (var i = 0; i < len - 1; i++) {
        dist += a.getAt(i).kmTo(a.getAt(i + 1));
    }
    return dist;
}


/* Limpar todo o mapa */
function LimparMapa(){
	if (ponto != undefined){
		ponto.setMap(null);
	}
	
	if (poligono != undefined){
		poligono.setMap(null);
	}
	
	if (poligonoP != undefined){
		poligonoP.setMap(null);
	}
	
	RemoverPontosMapa();
}

/* Remove o ponto do mapa */
function RemoverPontosMapa(){
	for (var p = 0; p < pontos.length; p++){
		if (pontos[p] != undefined){
			pontos[p].setMap(null);
		}
	}
	
	for (var p = 0; p < pontosP.length; p++){
		if (pontosP[p] != undefined){
			pontosP[p].setMap(null);
		}
	}
}


/* Função que plota o ponto no mapa */
function CarregarPontoNoMapa(latitude, longitude){
	if (ponto != undefined){
		ponto.setMap(null);
	}
	
	if (latitude != undefined && longitude != undefined){
		if (latitude != 0 && longitude != 0){
			var LatLng = new google.maps.LatLng(latitude, longitude);
			ponto = new google.maps.Marker({position:LatLng, icon: "Imagens/BolaAzul.png", map: mapa});
		} else {
			MsgAlerta(null, "Posi&ccedil;&atilde;o Inv&aacute;lida!");
		}	
	}
	
	ponto.setZIndex(99999999);
}
