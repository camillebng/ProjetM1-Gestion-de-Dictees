import mysql.connector
import re

# Fonction de tokénisation 
def tokenize(text):
	toks = re.findall(r"[\w\-]+'?",text)
	return toks

# Connection à la bdd
mydb = mysql.connector.connect(host = "localhost",database = "gr4m1IDL",user = "m2dilipem", password = "m2dilipem")
	
# Requête SQL
mycursor = mydb.cursor()
	
		# - Dictées version prof														
mycursor.execute("SELECT contenu prof, id_dict FROM version_prof WHERE type != 'mot' ")
									
contenu_prof = mycursor.fetchall()

		# - Dictées version élève
mycursor.execute("SELECT e.contenu_eleve FROM version_eleve e JOIN vversion_prof p ON p.id_dict = e.dict_id WHERE type != 'mot' ") 
									
contenu_eleve = mycursor.fetchall()
	
# Tokénisation des dictées dans leurs deux versions
toks_prof = []
toks_eleve = []

for dict in contenu_prof:
	toks_prof.append(tokenize(contenu_prof))
	
for dict in contenu_eleve:
	toks_eleve.append(tokenize(contenu_eleve))

# Insertion des dictées tokénisées dans la bdd
for tok in toks_prof:
	sql = "INSERT INTO toks_prof (tok_prof) VALUES (%s)"
	val = (tok,)
	
	mycursor.execute(sql,val)
	mydb.commit()

for tok in toks_eleve:
	sql = "INSERT INTO toks_eleve (tok_eleve) VALUES (%s)"
	val = (tok,)

	mycursor.execute(sql, val)
	mydb.commit
	

mydb.close()