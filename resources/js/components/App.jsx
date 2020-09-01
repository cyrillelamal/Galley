import React from 'react'
import Main from "./Main";
import axios from 'axios';
import UserContext from "../contexts/UserContext";
import Loading from "./Loading";

class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: true,
            user: null
        }

        this.setUser = (user) => this.setState({user})
    }

    loadUser() {
        axios.get('/api/reflect_user')
            .then(res => res.data)
            .then(({user}) => this.setState({user, loading: false}))
    }

    componentDidMount() {
        this.loadUser()
    }

    render() {
        if (this.state.loading) {
            return (
                <Loading>
                    Loading, please wait
                </Loading>
            )
        }

        const contextValue = {
            user: this.state.user,
            setUser: this.setUser
        }

        return (
            <UserContext.Provider value={contextValue}>
                    <Main/>
            </UserContext.Provider>
        )
    }
}

export default App
