import requests
import pandas as pd
from bs4 import BeautifulSoup

# Fonction pour récupérer les liens d'images à partir d'une URL de recherche d'images
def get_image_links(search_url):
    image_links = []
    response = requests.get(search_url)
    if response.status_code == 200:
        soup = BeautifulSoup(response.text, 'html.parser')
        # Inspectez la structure HTML de la page de résultats de recherche d'images pour identifier les balises contenant les liens d'images
        # et adaptez le code en conséquence
        for img in soup.find_all('img'):
            if img.has_attr('src'):
                image_links.append(img['src'])
    return image_links


# Charger les données depuis le fichier CSV
df = pd.read_csv("C:\\Users\\samue\\OneDrive\\Documents\\MIASHS\\Licence 3\\projet_Foot\\csv_pour_image.csv",sep=";")


# Assurez-vous que votre fichier CSV a des colonnes pour le nom complet (prénom + nom), le club et la ligue
# Supposons que ces colonnes soient nommées 'NomPrenom', 'Club' et 'Ligue'
noms_prenoms = df['nom'].tolist()
clubs = df['Club'].tolist()
ligues = df['Ligue'].tolist()

# URL de recherche d'images à partir de Google (ou tout autre moteur de recherche)
base_search_url = 'https://www.google.com/search?q='

# Création d'une liste pour stocker les données (noms, clubs, ligues et liens d'images)
donnees = []

# Recherche d'images pour chaque nom, club et ligue dans la liste
for nom_complet, club, ligue in zip(noms_prenoms, clubs, ligues):
    # Formatage de la requête de recherche
    # Formatage de la requête de recherche
    recherche_formatee = '+'.join([nom_complet, club, ligue])
    search_url = base_search_url + recherche_formatee + '&tbm=isch'
    # Récupération des liens d'images
    image_links = get_image_links(search_url)
    
    # Si au moins deux liens d'images sont trouvés, ajout des données à la liste
    if len(image_links) >= 2:
        donnees.append({'NomPrenom': nom_complet, 'Club': club, 'Ligue': ligue, 'LienImage': image_links[1]})  # Ajoute le deuxième lien trouvé
    else:
        print(f"Impossible de trouver deux liens d'images pour {nom_complet} ({club}, {ligue})")

# Création d'un DataFrame à partir des données
df_resultat = pd.DataFrame(donnees)

# Sauvegarde du DataFrame en tant que fichier CSV
df_resultat.to_csv("resultats_imagesclub+ligue+joueurs.csv", index=False)

print("Le fichier CSV a été créé avec succès.")
