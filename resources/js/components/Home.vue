<template>
  <div>

    <!-- Content -->
    <div class="container-fluid mt-3 ">
      <div class="card">

        <!-- Header -->
        <heading
          :title="title"
          :initialized="initialized"
          :loading="loading">
        </heading>

        <!-- Body -->
        <div class="card-body text-center" v-if="initialized">
          <h5>Welcome {{user.name}}</h5>
          <div v-if="!user.email_verified_at && !verification_sent" class="h6">
            Your email has not been verified, you may not have full access to all features.
            <button type="button" class="btn btn-sm btn-outline-info" :click="resendVerificationEmail(user.id)">Resend Verification Email</button>
          </div>
          <div v-if="!user.email_verified_at && verification_sent" class="h6">
            Verification Email Sent.
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>

  import Heading from './child_components/Heading';

  export default {

    components: { Heading },

    data() {
      return {
        title: 'Home',
        initialized: false,
        loading: false,
        user: '',

        verification_sent: false
      }
    },
    created() {
      this.fetchData();
    },
    methods: {

      // Get the updated home data.
      fetchData() {
        if(!this.loading) {
          this.loading = true;
          axios.get("/home",{params: {vue: true}}).then((response)=>{
            this.loading = false;
            this.initialized = true;
            this.user = response.data.user;
          });
        }
      },

      resendVerificationEmail(user_id) {
        axios.put('/home/'+user_id,{method: 'resend_verification_email'}).then(response => {
          this.verification_sent = true;
        }).catch(error => { this.fetchData(); });
      },

    }

  };

</script>
