import mysql.connector
import re

# Fonction de tokénisation 
def tokenize(text):
	if not text: return[]
	toks = re.findall(r"[\w\-]+'?",text)
	return toks

# Connection à la bdd
mydb = mysql.connector.connect(host = "localhost",database = "gr4m1IDL",user = "m2dilipem", password = "m2dilipem")
	

mycursor = mydb.cursor()
	
		# - Dictées version prof	
query_prof = """
				SELECT id_dict, contenu_prof 
				FROM version_prof p 
				WHERE p.type != 'mot'
			"""													
mycursor.execute(query_prof)
									
rows_prof = mycursor.fetchall()

for row in rows_prof:
	id_dict = row[0]
	content_prof = row[1]
	tokens = tokenize(content_prof)
	for token in tokens:
		sql = "INSERT INTO toks_prof (id_dict,tok_prof) VALUES (%s,%s)"
		val = (id_dict, token)
		mycursor.execute(sql,val)
	mydb.commit()

		# - Dictées version élève
query_eleve = """
				SELECT e.dict_fk, e.contenu_eleve 
				FROM version_eleve e 
				JOIN version_prof p ON p.id_dict = e.dict_fk 
				WHERE p.type != 'mot'
			"""
mycursor.execute(query_eleve) 
									
rows_eleve= mycursor.fetchall()

for row in rows_eleve:
	id_dict_origine = row[0]
	content_eleve = row[1]
	tokens = tokenize(content_eleve)
	
	for token in tokens:
		sql = "INSERT INTO toks_eleve (id_dict,tok_eleve) VALUES (%s,%s)"
		val = (id_dict_origine,token)
		mycursor.execute(sql,val)
	mydb.commit()	

mydb.close()