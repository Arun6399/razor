const express = require('express')

const router = express.Router()

const EmployeeController = require('../controllers/EmployeeController')

router.get('/',EmployeeController.index)

router.get('/new_find',EmployeeController.new_find)


router.post('/newss',EmployeeController.newss)

router.post('/login',EmployeeController.login)

router.get('/shows',EmployeeController.shows)

router.get('/liste',EmployeeController.liste)

router.get('/shaa',EmployeeController.shaa)

router.get('/results',EmployeeController.results)

router.get('perform',EmployeeController.perform)


router.post('/show',EmployeeController.show)
router.post('/store',EmployeeController.store)
router.post('/update',EmployeeController.update)
router.post('/delete',EmployeeController.destory)

module.exports = router
