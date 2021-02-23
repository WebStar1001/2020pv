<template>
    <div>
        <faq-section :questions="questions" :button_read_more="false"></faq-section>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                questions: [
                    {
                        question: 'How do I register and start a reading on GoPsys',
                        answer: 'To get started, press the Sign Up button on the top right-hand corner of our website.<br><br>After you have registered, simply Sign In and choose your favorite psychic and then click on their Chat or Mail button.'
                    },
                    {
                        question: 'How do I login to my account?',
                        answer: 'If you have already registered, just click on  Sign in on the top of GoPsys website.'
                    },
                    {
                        question: 'Choosing a User Name and Password',
                        answer: 'When you register on GoPsys,  you will need to choose a user name and password. You will need these to login each time you come back for more psychic readings.'
                    },
                    {
                        question: 'Forgot your username or password?',
                        answer: 'If you have forgotten your username or password, we will be happy to send you a new one. Simply go to “Log In” at the top of the page and click on the “Forgotten Password” link. A window will open with an option to request a username and/or password reset email.<br><br>You can change your username and password on the “My Account” page, located on the left side of setting page.'
                    },
                    {
                        question: 'What are my payment options?',
                        answer: 'We accept all major credit cards, including VISA, MasterCard, American Express and Discover. We also accept some pre-paid cards, gift cards and vouchers issued by these credit cards.<br><br>After you have used your initial free minutes, your credit card will be charged to fund your further readings. When your credit is running low, your GoPsys  account will be toped-up from your preferred payment method (credit card, Paypal, etc.). The top-up sum is set by default to be a sum sufficient for five minutes of chat. However, you have the option to change this amount before the reading begins. If you do not use the entire sum you have added to your GoPsys  account during a session, your account will remain in credit so that you can use the remainder of the funds for another session. If you do not wish for your GoPsys account to be topped-up when it’s running low, you may choose the option of “Quit reading” or “Manual recharge”. You can do this before each chat or set it in your profile settings.'
                    },
                    {
                        question: 'Choosing a psychic',
                        answer: 'GoPsys  are compassionate and highly experienced professionals that naturally like to help people.<br><br>To find a psychic advisor that is right for you, browse through the online psychics list on the homepage or select any of the categories or sub-categories pages. We are certain you will find a psychic you can trust.<br><br>To find out more about a psychic, just click on their image or name  to reveal their full profile.'
                    },
                    {
                        question: 'Is my personal information safe?',
                        answer: 'At GoPsys your personal information is treated with the strictest confidentially. Please see our Privacy Policy for full details.'
                    },
                    {
                        question: 'Free Minutes',
                        answer: 'Our advisors can email invitations offering free minutes. If you have a reading with a advisor who has sent you a free minutes invitation, the minutes given will be deducted from the total cost of your Chal.<br><br>We also occasionally offer free minutes to members. They are usually sent as a voucher or coupon by email. For further details, refer to the "Redeem vouchers" section below.<br><br>When your free minutes have been used, your call will return to the advisor advertised price per minute.<br><br>GoPsys  free minutes are only valid until the expiration date quoted on the voucher.<br><br>For all other offers and promotions, please refer to GoPsys Terms and Conditions'
                    },
                    {
                        question: 'How do I leave feedback for a reading?',
                        answer: 'After your reading you can leave feedback about the reading at a popup screen. You can also leave feedback up to 14 days after the reading in your "History & Feedback" page. Please be aware that we reserve the right to edit any feedback that is derogatory or defamatory.'
                    },
                ]
            }
        },
        created() {
        }
    }
</script>


<style lang="scss" scoped>
    .top-section {
        background: url('/images/bg/faq_header.jpg') no-repeat center top;
        background-size: cover;
    }
</style>
