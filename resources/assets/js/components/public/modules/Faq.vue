<template>
    <div class="faq-bg">
        <section class="container main-container faq-section">
            <div class="block-title mb-5 text-center">
                FAQ
            </div>

            <div class="faq-list">
                <div v-for="(item, i) in questions || questions_default" class="faq-item">
                    <b-button block href="#" v-b-toggle="'faq-' + i" variant="faq">{{ item.question }}</b-button>
                    <b-collapse :id="'faq-' + i"  role="tabpanel" class="faq-item-body">
                        <hr>
                        <div class="answer" v-html="item.answer"></div>
                    </b-collapse>
                </div>
            </div>

            <router-link
                    v-if="button_read_more"
                    :to="{ name: 'public.faq' }"
                    class="btn btn-success mx-auto mt-5"
                    style="display: table"
            >
                Read More
            </router-link>
        </section>
    </div>
</template>

<script>
    export default {
        props: {
            questions: {

            },
            button_read_more: {
                default: true
            }
        },
        data() {
            return {
                questions_default: [
                    {
                        question: 'How do I leave feedback for a reading?',
                        answer: 'After your reading you can leave feedback about the reading at a popup screen. You can also leave feedback up to 14 days after the reading in your "History & Feedback" page. Please be aware that we reserve the right to edit any feedback that is derogatory or defamatory.'
                    },
                    {
                        question: 'Is my personal information safe?',
                        answer: 'At GoPsys your personal information is treated with the strictest confidentially. Please see our Privacy Policy for full details.'
                    },
                    {
                        question: 'Choosing a psychic',
                        answer: 'GoPsys are compassionate and highly experienced professionals that naturally like to help people.<br><br>To find a psychic advisor that is right for you, browse through the online psychics list on the homepage or select any of the categories or sub-categories pages. We are certain you will find a psychic you can trust.<br>To find out more about a psychic, just click on their image or name to reveal their full profile.'
                    },
                    {
                        question: 'What are my payment options?',
                        answer: 'We accept all major credit cards, including VISA, MasterCard, American Express and Discover. We also accept some pre-paid cards, gift cards and vouchers issued by these credit cards.<br><br>After you have used your initial free minutes, your credit card will be charged to fund your further readings. When your credit is running low, your GoPsys account will be toped-up from your preferred payment method (credit card, Paypal, etc.). The top-up sum is set by default to be a sum sufficient for five minutes of chat. However, you have the option to change this amount before the reading begins. If you do not use the entire sum you have added to your GoPsys account during a session, your account will remain in credit so that you can use the remainder of the funds for another session. If you do not wish for your GoPsys account to be topped-up when it’s running low, you may choose the option of “Quit reading” or “Manual recharge”. You can do this before each chat or set it in your profile settings.'
                    },
                    {
                        question: 'How do I register and start a reading on GoPsys',
                        answer: 'To get started, press the Sign Up button on the top right-hand corner of our website.<br><br>After you have registered, simply Sign In and choose your favorite psychic and then click on their Chat or Mail button.'
                    },
                ]
            };
        }
    }
</script>

<style lang="scss" scoped>
    .faq-bg {
        background-color: #edf1f2;
    }

    .faq-section {
        max-width: 770px;
    }

    .faq-list {
        .faq-item + .faq-item {
            margin-top: 20px;
        }
        .btn-faq {
            display: block;
            font-family: 'Montserrat', sans-serif;
            color: #212B36;
            border: 1px solid #EEEEEE;
            text-align: left;
            padding: 23px 70px 23px 40px;
            font-size: 18px;
            font-weight: bold;
            position: relative;
            white-space: inherit;
            background: #ffffff;
            box-shadow: none;
            &:hover {
                border-color: #00bff0;
            }
            &[aria-expanded="true"]:hover {
                border: 1px solid #EEEEEE;
            }
            &:before,
            &:after {
                position: absolute;
                content: '';
                width: 2px;
                height: 16px;
                background: #86cd03;
                top: calc(50% - 16px / 2);
                right: 52px;
                transition: all .3s;
                transform-origin: 50% 50%;
            }
            &:before {
                transform: rotate(90deg);
            }
            &[aria-expanded="true"]:before {
                transform: rotate(45deg);
                background: #212B36;
            }
            &[aria-expanded="true"]:after {
                transform: rotate(-45deg);
                background: #212B36;
            }
        }
    }

    .faq-item-body {
        text-align: left;
        padding: 0 40px 0 40px;
        background: #FDFDFD;
        hr {
            margin-top: 0;
        }
    }

    .answer {
        padding-bottom: 1rem;
    }

    .btn-success {
        padding: 10px 90px;
    }
</style>
