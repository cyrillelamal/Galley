import React from 'react'
import Loading from "../components/Loading";
import {Redirect} from "react-router-dom";
import axios from 'axios'
import UserContext from "../contexts/UserContext";

class Logout extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: true
        }
    }

    componentDidMount() {
        axios.post('/logout').then(() => {
            const {setUser} = this.context

            this.setState({loading: false})

            setUser(null)
        })
    }

    render() {
        if (this.state.loading) {
            return (
                <Loading>
                    Bye
                </Loading>
            )
        }

        return (
            <Redirect to={'/'}/>
        )
    }
}

Logout.contextType = UserContext

export default Logout
