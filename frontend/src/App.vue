<template>
  <div id="app">
    <header class="header">
      <div class="container">
        <h1>📋 Gestion de Contacts</h1>
        <p>Application Vue.js + PHP + MySQL</p>
      </div>
    </header>

    <main class="container">
      <ContactForm @contact-added="loadContacts" />
      
      <section class="contacts-section">
        <h2>Liste des contacts ({{ contacts.length }})</h2>
        
        <div v-if="loading" class="loading">
          Chargement...
        </div>
        
        <div v-else-if="error" class="error">
          {{ error }}
        </div>
        
        <div v-else-if="contacts.length === 0" class="empty">
          Aucun contact pour le moment
        </div>
        
        <div v-else class="contacts-list">
          <div v-for="contact in contacts" :key="contact.id" class="contact-card">
            <div class="contact-header">
              <h3>{{ contact.prenom }} {{ contact.nom }}</h3>
              <span class="date">{{ formatDate(contact.date_creation) }}</span>
            </div>
            <p class="commentaire">{{ contact.commentaire }}</p>
          </div>
        </div>
      </section>
    </main>

    <footer class="footer">
      <p>© 2025 - Application de gestion de contacts</p>
    </footer>
  </div>
</template>

<script>
import ContactForm from './components/ContactForm.vue'

export default {
  name: 'App',
  components: {
    ContactForm
  },
  data() {
    return {
      contacts: [],
      loading: false,
      error: null,
      apiUrl: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080/api'
    }
  },
  mounted() {
    this.loadContacts()
  },
  methods: {
    async loadContacts() {
      this.loading = true
      this.error = null
      
      try {
        const response = await fetch(`${this.apiUrl}/contacts.php`)
        const data = await response.json()
        
        if (data.success) {
          this.contacts = data.data
        } else {
          this.error = data.message || 'Erreur lors du chargement'
        }
      } catch (err) {
        this.error = 'Impossible de se connecter à l\'API'
        console.error('Erreur:', err)
      } finally {
        this.loading = false
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString)
      return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  color: #333;
}

#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.header {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  padding: 30px 0;
  text-align: center;
  color: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.header h1 {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.header p {
  font-size: 1.1rem;
  opacity: 0.9;
}

main {
  flex: 1;
  padding: 40px 20px;
}

.contacts-section {
  margin-top: 40px;
}

.contacts-section h2 {
  color: white;
  font-size: 1.8rem;
  margin-bottom: 20px;
  text-align: center;
}

.loading, .error, .empty {
  text-align: center;
  padding: 40px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.error {
  color: #e74c3c;
  font-weight: bold;
}

.contacts-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
}

.contact-card {
  background: white;
  border-radius: 10px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.contact-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
}

.contact-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  border-bottom: 2px solid #667eea;
  padding-bottom: 10px;
}

.contact-header h3 {
  color: #667eea;
  font-size: 1.3rem;
}

.date {
  font-size: 0.85rem;
  color: #999;
}

.email {
  color: #555;
  margin: 10px 0;
  font-size: 0.95rem;
}

.commentaire {
  color: #666;
  line-height: 1.6;
  margin-top: 15px;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 5px;
  border-left: 4px solid #667eea;
}

.footer {
  background: rgba(0, 0, 0, 0.2);
  color: white;
  text-align: center;
  padding: 20px;
  margin-top: 40px;
}

@media (max-width: 768px) {
  .header h1 {
    font-size: 2rem;
  }
  
  .contacts-list {
    grid-template-columns: 1fr;
  }
  
  .contact-header {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .date {
    margin-top: 5px;
  }
}
</style>