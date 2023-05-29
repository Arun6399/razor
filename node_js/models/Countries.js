const mongoose = require('mongoose')
const Schema = mongoose.Schema

const CountriesSchema = new Schema({
   
    id:{
        type:Number
    },
    contry_name:{
        type:String
    },
    currency:{
        type:String
    },
    currencycode:{
        type:String
    },
    phonecode:{
        type:String
    }
},{timestamps:true})

const Countries = mongoose.model('countries',CountriesSchema)
module.exports = Countries