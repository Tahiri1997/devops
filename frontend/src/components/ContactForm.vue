<template>
  <section class="form-section">
    <h2>➕ Ajouter un contact</h2>
    
    <form @submit.prevent="submitForm" class="contact-form">
      <div class="form-row">
        <div class="form-group">
          <label for="nom">Nom *</label>
          <input
            type="text"
            id="nom"
            v-model="form.nom"
            placeholder="Dupont"
            required
            :disabled="loading"
          >
        </div>
        
        <div class="form-group">
          <label for="prenom">Prénom *</label>
          <input
            type="text"
            id="prenom"
            v-model="form.prenom"
            placeholder="Jean"
            required
            :disabled="loading"
          >
        </div>
      </div>
      
      <!-- Email retiré : champ non requis pour cette application -->
      
      <div class="form-group">
        <label for="commentaire">Commentaire *</label>
        <textarea
          id="commentaire"
          v-model="form.commentaire"
          placeholder="Votre message..."
          rows="4"
          required
          :disabled="loading"
        ></textarea>
      </div>
      
      <div v-if="message" :class="['message', message.type]">
        {{ message.text }}
      </div>
      
      <button type="submit" class="btn-submit" :disabled="loading">
        {{ loading ? '⏳ Envoi en cours...' : '📤 Envoyer' }}
      </button>
    </form>
  </section>
</template>

<script>
export default {
  name: 'ContactForm',
  data() {
    return {
      form: {
        nom: '',
        prenom: '',
        commentaire: ''
      },
      loading: false,
      message: null,
      apiUrl: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080/api'
    }
  },
  methods: {
    async submitForm() {
      this.loading = true
      this.message = null
      
      try {
        const response = await fetch(`${this.apiUrl}/contacts.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(this.form)
        })
        
        const data = await response.json()
        
        if (data.success) {
          this.message = {
            type: 'success',
            text: '✅ Contact ajouté avec succès !'
          }
          
          // Réinitialiser le formulaire
          this.form = {
            nom: '',
            prenom: '',
            commentaire: ''
          }
          
          // Émettre l'événement pour rafraîchir la liste
          this.$emit('contact-added')
          
          // Cacher le message après 3 secondes
          setTimeout(() => {
            this.message = null
          }, 3000)
        } else {
          this.message = {
            type: 'error',
            text: `❌ ${data.message || 'Erreur lors de l\'ajout'}`
          }
        }
      } catch (err) {
        this.message = {
          type: 'error',
          text: '❌ Impossible de se connecter à l\'API'
        }
        console.error('Erreur:', err)
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

<style scoped>
.form-section {
  background: white;
  border-radius: 15px;
  padding: 35px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  margin-bottom: 40px;
}

.form-section h2 {
  color: #667eea;
  font-size: 1.8rem;
  margin-bottom: 25px;
  text-align: center;
}

.contact-form {
  max-width: 700px;
  margin: 0 auto;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  color: #555;
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 0.95rem;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 1rem;
  font-family: inherit;
  transition: all 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group input:disabled,
.form-group textarea:disabled {
  background-color: #f5f5f5;
  cursor: not-allowed;
}

.form-group textarea {
  resize: vertical;
  min-height: 100px;
}

.message {
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-weight: 500;
  text-align: center;
}

.message.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.message.error {
  background-color: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

.btn-submit {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(102, 126, 234, 0.3);
}

.btn-submit:active:not(:disabled) {
  transform: translateY(0);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 768px) {
  .form-section {
    padding: 25px 20px;
  }
  
  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
  }
  
  .form-section h2 {
    font-size: 1.5rem;
  }
}
</style>