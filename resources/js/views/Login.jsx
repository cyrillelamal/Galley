import React from 'react'
import axios from 'axios'
import {Link, Redirect} from "react-router-dom";
import UserContext from "../contexts/UserContext";

class Login extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            email: '',
            password: ''
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
    }

    componentDidMount() {
        this.loadCsrfCookie()
    }

    loadCsrfCookie() {
        axios.get('/sanctum/csrf-cookie').then()
    }

    handleChange({target}) {
        const {name, value} = target

        this.setState({[name]: value})
    }

    handleSubmit(e) {
        e.preventDefault()

        const {email, password} = this.state

        const data = {email, password}

        axios.post('/login', data).then(res => {
            if (res.status === 204) {
                const {setUser} = this.context

                setUser(true)
            }
        })
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
                                    Login
                                </p>
                            </header>
                            <div className="card-content">

                                <form action="" method="post" onSubmit={this.handleSubmit}>
                                    {/* identifier */}
                                    <div className="field">
                                        <p className="control has-icons-left">
                                            <input
                                                className="input" type="email" placeholder="Email"
                                                name="email"
                                                value={this.state.email}
                                                onChange={this.handleChange}
                                            />
                                            <span className="icon is-small is-left">
                                                <i className="fas fa-envelope"/>
                                            </span>
                                        </p>
                                    </div>
                                    {/* password */}
                                    <div className="field">
                                        <p className="control has-icons-left">
                                            <input
                                                className="input" type="password" placeholder="Password"
                                                name="password"
                                                value={this.state.password}
                                                onChange={this.handleChange}
                                            />
                                            <span className="icon is-small is-left">
                                                <i className="fas fa-unlock"/>
                                            </span>
                                        </p>
                                    </div>
                                    {/* submit */}
                                    <div className="field">
                                        <p className="control">
                                            <button className="button is-success" role="submit">
                                                Login
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
                                    <Link to={'/signup'} className="button is-info">
                                        Or sign up
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

Login.contextType = UserContext

export default Login
