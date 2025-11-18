# RestauBoost Mobile App 📱

Application mobile React Native pour RestauBoost - Plateforme de marketing pour restaurants.

## 🚀 Fonctionnalités

### ✨ Principales Fonctionnalités

- **Authentification sécurisée** avec JWT et AsyncStorage
- **Dashboard interactif** avec statistiques en temps réel
- **Gestion des clients** avec filtres avancés (VIP, à risque)
- **Profils clients détaillés** avec scores RFM
- **Générateur de contenu IA** pour les réseaux sociaux
- **Navigation fluide** avec React Navigation
- **Design moderne** avec gradients et animations
- **Mode hors-ligne** avec persistence locale

### 📱 Écrans Disponibles

1. **Écrans d'Authentification**
   - Connexion (LoginScreen)
   - Inscription (RegisterScreen)

2. **Dashboard**
   - Statistiques globales (clients, VIP, à risque, emails)
   - Graphiques de visites
   - Actions rapides
   - Activité récente

3. **Gestion Clients**
   - Liste des clients avec recherche
   - Filtres (Tous, VIP, À risque)
   - Profil client détaillé
   - Score RFM avec visualisation
   - Historique des visites
   - Actions rapides (appel, email, SMS)

4. **Générateur IA**
   - Génération de contenu pour Facebook, Instagram, Twitter, Email
   - Sélection du ton (Professionnel, Amical, Enthousiaste, Décontracté)
   - Copie et partage faciles

## 🛠 Technologies Utilisées

### Core
- **React Native** 0.73.2
- **React** 18.2.0

### Navigation
- **@react-navigation/native** ^6.1.9
- **@react-navigation/stack** ^6.3.20
- **@react-navigation/bottom-tabs** ^6.5.11
- **@react-navigation/drawer** ^6.6.6

### UI & Design
- **react-native-linear-gradient** ^2.8.3 - Gradients
- **react-native-vector-icons** ^10.0.3 - Icônes
- **react-native-chart-kit** ^6.12.0 - Graphiques
- **react-native-svg** ^14.1.0 - SVG support

### State & Storage
- **@react-native-async-storage/async-storage** ^1.21.0
- **Context API** pour la gestion d'état

### Networking
- **axios** ^1.6.5

### Autres
- **react-native-toast-message** ^2.2.0 - Notifications
- **react-native-gesture-handler** ^2.14.1
- **react-native-safe-area-context** ^4.8.2

## 📂 Structure du Projet

```
mobile/
├── src/
│   ├── components/          # Composants réutilisables
│   │   ├── Badge.js         # Composant badge avec variants
│   │   ├── Button.js        # Bouton avec gradients
│   │   ├── Card.js          # Carte avec styles
│   │   ├── EmptyState.js    # État vide
│   │   ├── Input.js         # Input avec validation
│   │   ├── Loading.js       # Indicateur de chargement
│   │   └── index.js         # Export central
│   │
│   ├── screens/             # Écrans de l'application
│   │   ├── Auth/
│   │   │   ├── LoginScreen.js
│   │   │   └── RegisterScreen.js
│   │   ├── Dashboard/
│   │   │   └── DashboardScreen.js
│   │   ├── Customers/
│   │   │   ├── CustomersListScreen.js
│   │   │   └── CustomerDetailScreen.js
│   │   ├── AI/
│   │   │   └── AIGeneratorScreen.js
│   │   └── LoadingScreen.js
│   │
│   ├── navigation/          # Configuration navigation
│   │   └── AppNavigator.js  # Stack & Tab navigation
│   │
│   ├── context/             # State management
│   │   └── AuthContext.js   # Contexte authentification
│   │
│   ├── services/            # Services API
│   │   └── api.js           # Configuration Axios
│   │
│   └── utils/               # Utilitaires
│       ├── theme.js         # Thème (couleurs, typographie)
│       └── animations.js    # Animations helpers
│
├── App.js                   # Point d'entrée
├── package.json
└── README.md
```

## 🎨 Thème & Design

### Palette de Couleurs

```javascript
primary: '#6366f1'      // Indigo
primaryDark: '#4f46e5'
success: '#10b981'      // Green
warning: '#f59e0b'      // Amber
danger: '#ef4444'       // Red
info: '#3b82f6'         // Blue
```

### Gradients

- **Primary:** `['#6366f1', '#8b5cf6']`
- **Success:** `['#10b981', '#059669']`
- **Warning:** `['#f59e0b', '#d97706']`
- **Danger:** `['#ef4444', '#dc2626']`

### Typographie

- **Font:** Inter (système)
- **Tailles:** xs (12), sm (14), base (16), lg (18), xl (20), 2xl (24)

## 🔧 Installation

### Prérequis

- Node.js >= 18
- npm ou yarn
- React Native CLI
- Android Studio (pour Android)
- Xcode (pour iOS, macOS uniquement)

### Étapes d'Installation

1. **Naviguer vers le dossier mobile**
   ```bash
   cd mobile
   ```

2. **Installer les dépendances**
   ```bash
   npm install
   # ou
   yarn install
   ```

3. **Installer les pods iOS (macOS uniquement)**
   ```bash
   cd ios
   pod install
   cd ..
   ```

4. **Configurer l'API Backend**

   Modifier l'URL de l'API dans `src/services/api.js`:
   ```javascript
   const API_BASE_URL = 'http://VOTRE_IP:8000/api';
   ```

   Pour Android Emulator: `http://10.0.2.2:8000/api`
   Pour iOS Simulator: `http://localhost:8000/api`
   Pour appareil physique: `http://VOTRE_IP_LOCAL:8000/api`

## 🚀 Lancement

### Mode Développement

#### Android
```bash
npm run android
# ou
npx react-native run-android
```

#### iOS (macOS uniquement)
```bash
npm run ios
# ou
npx react-native run-ios
```

### Metro Bundler
```bash
npm start
# ou
npx react-native start
```

## 🧪 Données de Test

### Compte de démonstration
- **Email:** `demo@restauboost.com`
- **Mot de passe:** `demo1234`

### Clients Mock
L'application utilise des données mock pour le développement:
- Jean Dupont (VIP) - 24 visites
- Marie Martin (Super VIP) - 42 visites
- Pierre Durand (Regular, À risque) - 8 visites

## 📱 Fonctionnalités Techniques

### Authentification
- JWT token stocké dans AsyncStorage
- Auto-login au démarrage
- Refresh token automatique
- Logout sécurisé

### API Integration
Service API centralisé avec:
- Intercepteurs Axios pour les tokens
- Gestion des erreurs globale
- Retry automatique
- Timeout configurable

### Animations
Bibliothèque d'animations personnalisée:
- Fade In/Out
- Slide (top, bottom, left, right)
- Scale & Bounce
- Pulse (loop)
- Shake
- Rotate
- Stagger pour les listes

### Composants Réutilisables
- **Button:** Variants (primary, secondary, outline, ghost), sizes, gradients
- **Input:** Validation, icônes, show/hide password
- **Card:** Variants (default, elevated, outlined)
- **Badge:** Colors, sizes, avec/sans icône
- **Loading:** États de chargement
- **EmptyState:** États vides avec actions

## 🔐 Sécurité

- Tokens JWT sécurisés
- Stockage crypté avec AsyncStorage
- Validation des entrées côté client
- Protection CSRF
- HTTPS requis en production

## 🌐 Configuration API

### Endpoints Disponibles

```javascript
// Authentification
authApi.login(email, password)
authApi.register(name, email, password, restaurant_name)
authApi.logout()

// Clients
customersApi.getAll(params)
customersApi.getById(id)
customersApi.create(data)
customersApi.update(id, data)
customersApi.delete(id)

// IA
aiApi.generateContent(data)
aiApi.analyzeSentiment(data)

// Campagnes
campaignsApi.getAll()
campaignsApi.getById(id)
campaignsApi.create(data)
```

## 📊 Performance

- **Lazy Loading** des écrans
- **Memoization** des composants
- **Optimisation des listes** avec FlatList
- **Images optimisées**
- **Animations natives** avec useNativeDriver

## 🐛 Debugging

### React Native Debugger
```bash
npm run debugger
```

### Logs
```bash
# Android
npx react-native log-android

# iOS
npx react-native log-ios
```

### Flipper
L'application est compatible avec Flipper pour le debugging avancé.

## 🚢 Build Production

### Android APK
```bash
cd android
./gradlew assembleRelease
```

### Android Bundle (Play Store)
```bash
cd android
./gradlew bundleRelease
```

### iOS (macOS uniquement)
1. Ouvrir `ios/RestauBoostMobile.xcworkspace` dans Xcode
2. Product > Archive
3. Distribute App

## 📝 To-Do / Améliorations Futures

- [ ] Push notifications
- [ ] Mode hors-ligne complet
- [ ] Synchronisation en arrière-plan
- [ ] Biométrie (Face ID / Touch ID)
- [ ] Partage natif
- [ ] Deep linking
- [ ] Tests unitaires (Jest)
- [ ] Tests E2E (Detox)
- [ ] Internationalisation (i18n)
- [ ] Dark mode
- [ ] Analytics
- [ ] Crash reporting (Sentry)

## 🤝 Contribution

1. Fork le projet
2. Créer une branche (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT.

## 🆘 Support

Pour toute question ou problème:
- 📧 Email: support@restauboost.com
- 📱 Discord: RestauBoost Community
- 📖 Documentation: https://docs.restauboost.com

## 🎉 Remerciements

- React Native Community
- Expo Team
- React Navigation Team
- Tous les contributeurs open-source

---

Développé avec ❤️ par l'équipe RestauBoost
