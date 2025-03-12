# TodoApp

TodoApp est une application de gestion de tâches développée avec Symfony. Elle utilise une base de données MySQL pour stocker les tâches.

## Prérequis

- PHP 8.0 ou supérieur
- Composer
- MySQL

## Installation

### Backend (Symfony)

1. **Clonez le dépôt :**
   ```sh
   git clone https://github.com/Ales230/TodoApp.git
   cd TodoApp
   ```

2. **Installer les dépendances :**
   ```sh
   composer install
   npm install
   ```

3. **Configurez la base de données dans le fichier `.env` :**
   ```sh
   DATABASE_URL="mysql://root@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
   ```

4. **Créez la base de données et exécutez les migrations :**
   ```sh
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Démarrez le serveur Symfony :**
   ```sh
   symfony server:start
   ```

---

## 🔧 API REST
L’API permet de récupérer la liste des tâches filtrées.

📌 **Endpoint:** `GET /task/api/tasks`

### **Filtres disponibles :**
| Paramètre  | Valeur    | Effet                         |
|------------|----------|------------------------------|
| `status`   | `all`     | Toutes les tâches          |
| `status`   | `done`    | Tâches terminées           |
| `status`   | `pending` | Tâches en attente          |

📌 **Exemple d’appel API avec `fetch` :**
```js
fetch('/task/api/tasks?status=done')
  .then(response => response.json())
  .then(data => console.log(data));
```

---

## 💻 Développement
### **🛠 Technologies utilisées**
- Symfony 5.10.4
- Doctrine ORM (MySQL)
- Twig (Template Engine)
- Bootstrap (UI)
- API REST en JSON
