const express = require('express');
const cors = require('cors');

const app = express();
const port = 3333;

app.use(cors());
app.use(express.json());

app.post('/message', (req, res) => {
    const { tel, verification_number } = req.body;

    try{
        if(!tel || !verification_number) {
            res.status(400).json({ error: "tel or verfication number are not provided" });
        }
        if(!/^\d{6}$/.test(verification_number)) {
            res.status(200).json({ error: "You provided an invalid verification number" });  
        }
        if(!/^0[5-7]\d{8}$/.test(tel)) {
            res.status(200).json({ error: "You provided an invalid Moroccan telephone number" });  
        }
        console.log(`${tel}: your verification number is ${verification_number}`);
        res.status(200).json({ message: "success" });    
    }catch(e) {
        res.status(500).json({ error: `${e}` });
    }
});

app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
