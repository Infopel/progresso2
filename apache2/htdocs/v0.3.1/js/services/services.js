app.factory('artigoService', function($resource){
	//return $resource('https://dragons-api.herokuapp.com/api/dragons');
	return $resource('http://localhost/api/artigo');
})