from bottle import route, run
from bottle import response, request
import json
import sqlite3

@route('/hello')
def hello():
	return "Hola mundo!"
	
@route('/saludos')
def saludos():
	return "Saludos!"
	
@route('/crearBaseDatos')
def crearBaseDatos():
	con_bd = sqlite3.connect('database.sqlite')
	cursor_principal = con_bd.cursor()
	SQL = 'Create Table if not exists registro(Personas int, fecha TEXT)'
	cursor_principal.execute(SQL)
	con_bd.commit()
	cursor_principal.close()
	con_bd.close()
	return "hecho!"
	
@route('/jsonencode')
def jsonencode():
	response.set_header('Content-Type', 'application/json')
	a = {"clave":"valor"}
	return json.dumps(a)
	
@route('/jsondecode',method='POST')
def jsondecode():
	response.set_header('Content-Type', 'application/json')
	if not request.json:
		return '{"R":400,"D":"Cuerpo Vacio"}'
	a = {"R" : 200, "D" : request.json};
	return json.dumps(a)
	
@route('/api/agregarRegistro',method='POST')
def agregarRegistro():
		response.set_header('Content-Type', 'application/json')
		if not request.json:
			return '{"R":400,"D":"Cuerpo Vacio"}'
		if not 'personas' in request.json:
			return '{"R":400,"D":"No hay personas"}'
		
		if not str(request.json['personas']).isnumeric():
			return '{"R":400,"D":"personas no es un número"}'
		
		#Si llega aqui es que si hay personas
		con_bd = sqlite3.connect('database.sqlite')
		cursor_principal = con_bd.cursor()
		SQL = "insert into registro values (?, datetime('now'))"
		cursor_principal.execute(SQL,(request.json['personas'],))
		con_bd.commit()
		cursor_principal.close()
		con_bd.close()
		
		a = { "R" : 201}
		return json.dumps(a)



run(server='waitress',host='localhost', port=8080, debug=True)
