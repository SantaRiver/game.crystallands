import axios from 'axios'

import { ResponseWrapper, ErrorWrapper } from './util'

export class AuthService {
    /**
     ******************************
     * @API
     ******************************
     */

    static async login (loginRequest) {
        try {
            const response = await axios.post('login', loginRequest)
            console.log(response);
            return new ResponseWrapper(response, response.data.data)
        } catch (error) {
            throw new ErrorWrapper(error)
        }
    }

}
