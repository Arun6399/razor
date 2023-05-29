const mongoose = require('mongoose')
const Schema = mongoose.Schema

const currencySchema = new Schema ({

   
    currency_name:
    {
        type:String
    },
    currency_symbol:{
        type:String
    },
    asset_type:{
        type:String
    },
    priority:{
        type:String
    },
    user_id:{
        type:Number
    }
//     type:
//     {
//         type:String
//     },
//     coin_type:{
//         type:String
//     },
//     image:{
//         type:String
//     },
//     status:
//     {
//         type:Number
//     },
//     verify_request:
//     {
//         type:Number
//     },
//     created:
//    { type:Number},
//    contract_address:
//    {
//     type:String
//    },
//    reserve_Amount:{
//     type:Number
//    },
//    online_usdprice:{
//     type:Number
//    },
//    min_deposit_limit:{
//     type:Number
//    },
//    max_deposit_limit:
//    {
//     type:Number
//    },
//    min_withdraw_limit:
//    {
//     type:Number
//    },
//    max_withdraw_limit:{
//     type:Number
//    },
//    deposit_fees:
//    {
//     type:String
//    },
//    deposit_max_fees:
//    {
//     type:Number
//    },
//    withdraw_fees_type:{
//     type:Number
//    },
//    withdraw_fees:{
//     type:Number
//    },
//    maker_fee:
//    {
//     type:Number
//    },
//    taker_fee:{
//     type:Number
//    },
//    oneday_change:
//    {
//     type:String
//    },sort_order:{
//     type:String
//    },
//    market_cap_change_percentage_24h:{
//     type:String
//    },
//    move_process_admin:
//    {
//     type:String
//    },
//    token_price:{
//     type:String
//    },
//    move_admin:{
//     type:String
//    },
//    show_home:{
//     type:String
//    },
//    deposit_status:{
//     type:String
//    },
//    withdraw_status:{
//     type:String
//    },
//    expiry_date:{
//     type:String
//    },
//    show_decimal:{
//     type:Number
//    },
//    ethers_status:{
//     type:Number
//    }


},{timestamp: true})

const Currency = mongoose.model('currency',currencySchema)
module.exports = Currency