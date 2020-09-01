import React from 'react'
import axios from 'axios'
import {Link, Redirect} from "react-router-dom";
import UserContext from "../contexts/UserContext";

class Signup extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            email: '',
            password: '',
            password_confirmation: '',

            passwordsDiffer: false
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
    }

    componentDidMount() {
        this.loadCsrfCookie()
    }

    loadCsrfCookie() {
        axios.get('/sanctum/csrf-cookie')
    }

    handleChange({target}) {
        const {name, value} = target

        this.setState((state) => {
            let newState = {
                [name]: value
            }

            if (state.passwordsDiffer && name !== 'email') {
                newState.passwordsDiffer = false
            }

            return newState
        })
    }

    handleSubmit(e) {
        e.preventDefault()

        const {email, password, password_confirmation} = this.state

        if (password !== password_confirmation) {
            this.setState({passwordsDiffer: true})
            return null
        }

        const data = {email, password, password_confirmation}

        axios.post('/register', data)
            .then(res => console.log(res))
            .catch(reason => console.error(reason))
    }

    render() {
        const {user} = this.context

        if (user) {
            return (
                <Redirect to={'/tasks'}/>
            )
        }

        return (
            <section className="hero is-info is-fullheight">
                <div className="hero-body">
                    <div className="container has-text-centered">
                        <div className="card">
                            <header className="card-header">
                                <p className="card-header-title">
                                    Sign up
                                </p>
                            </header>
                            <div className="card-content">
                                <form action="" method="post" onSubmit={this.handleSubmit}>
                                    {/* identifier */}
                                    <div className="field">
                                        <p className="control has-icons-left">
                                            <input
                                                className="input" type="email" placeholder="Email"
                                                name="email" value={this.state.email}
                                                onChange={this.handleChange}
                                            />
                                            <span className="icon is-small is-left">
                                                <i className="fas fa-envelope"/>
                                            </span>
                                        </p>
                                    </div>
                                    {/* password */}
                                    {this.state.passwordsDiffer && (
                                        <div className="notification is-danger">
                                            Passwords differ
                                        </div>
                                    )}
                                    <div className="field">
                                        <p className="control has-icons-left">
                                            <input
                                                className="input" type="password" placeholder="Password"
                                                name="password" value={this.state.password}
                                                onChange={this.handleChange}
                                            />
                                            <span className="icon is-small is-left">
                                                <i className="fas fa-unlock"/>
                                            </span>
                                        </p>
                                    </div>
                                    <div className="field">
                                        <p className="control has-icons-left">
                                            <input
                                                className="input" type="password" placeholder="Repeat your password"
                                                name="password_confirmation" value={this.state.password_confirmation}
                                                onChange={this.handleChange}
                                            />
                                            <span className="icon is-small is-left">
                                                <i className="fas fa-unlock"/>
                                            </span>
                                        </p>
                                    </div>
                                    {/* submit */}
                                    <div className="field has-text-left">
                                        <p className="control">
                                            <button className="button is-success">
                                                Sign up
                                            </button>
                                        </p>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="hero-foot">
                    <nav className="tabs is-boxed is-fullwidth">
                        <div className="container">
                            <ul>
                                <li>
                                    <Link to={'/login'} className="button is-info">
                                        Or login
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </section>
        );
    }
}

Signup.contextType = UserContext

export default Signup
