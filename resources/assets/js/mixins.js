const MyMixins = {
    created: function () {
    },
    methods: {
        toFixed: function (num, fixed) {
            if (num) {
                if (num === 0) {
                    return '0.00';
                }

                const re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');

                return parseFloat(Math.abs(num).toString().match(re)[0], 10).toFixed(fixed);
            } else {
                return '0.00'
            }
        }
    }
};

export default MyMixins;