import spacy
import mysql.connector
import sys

# Récupération de l'ID de la copie élève envoyé par PHP
if len(sys.argv) < 2:
    sys.exit(1)

id_eleve = sys.argv[1] # ID de version_eleve

try:
    # Chargement du modèle français (version small)
    nlp = spacy.load("fr_core_news_sm")
    
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",       
        password="",      
        database="gr4m1IDL",
        charset="utf8mb4" # pour les accents
    )
    mycursor = mydb.cursor(dictionary=True)

    # Récupération du texte version prof lié à la version élève
    query = """
        SELECT p.contenu_prof, v.dict_fk 
        FROM version_eleve v
        JOIN version_prof p ON v.dict_fk = p.id_dict
        WHERE v.id_dict_eleve = %s
    """
    mycursor.execute(query, (id_eleve,))
    res = mycursor.fetchone()
    
    if res and res['contenu_prof']:
        # Analyse Spacy sur la version prof
        doc = nlp(res['contenu_prof'])
        tokens_spacy = [t for t in doc if not t.is_space and not t.is_punct]
        
        # Récupération des tokens de l'élève
        dict_fk = res['dict_fk']
        mycursor.execute("SELECT id_toks_eleve FROM toks_eleve WHERE id_dict_fk = %s ORDER BY position_eleve ASC", (dict_fk,))
        tokens_db = mycursor.fetchall()
        

        updates = []
        for t_spacy, t_db in zip(tokens_spacy, tokens_db):
            updates.append((t_spacy.pos_, t_db['id_toks_eleve']))
        
        if updates:

            sql_update = "UPDATE toks_eleve SET pos_tok = %s WHERE id_toks_eleve = %s"
            mycursor.executemany(sql_update, updates)
            mydb.commit()

except Exception as e:

    print(f"Erreur Python : {e}")

finally:
    if 'mydb' in locals() and mydb.is_connected():
        mycursor.close()
        mydb.close()