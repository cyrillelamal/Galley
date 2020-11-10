import React from 'react'
import axios from 'axios'

class ListingForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            name: ''
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
        this.clearForm = this.clearForm.bind(this)
    }

    clearForm() {
        this.setState({name: ''})
    }

    handleSubmit(e) {
        e.preventDefault()

        const {name} = this.state

        const data = {name}

        axios.post('/api/listings', data)
            .then(res => res.data)
            .then(listing => {
                this.clearForm()
                this.props.addListing(listing)
            })
    }

    handleChange({target}) {
        const {name, value} = target

        this.setState({[name]: value})
    }

    render() {
        return (
            <div className="card">
                <header className="card-header">
                    <p className="card-header-title">
                        New list
                    </p>
                </header>
                <div className="card-content">
                    <form onSubmit={this.handleSubmit} autoComplete={"off"}>
                        <div className="field">
                            <div className="control">
                                <input
                                    name="name" id="name"
                                    value={this.state.name}
                                    onChange={this.handleChange}
                                    type="text" placeholder="New listing"
                                    className="input"
                                />
                            </div>
                        </div>
                        <div className="field">
                            <div className="control">
                                <button className="button is-primary" type="submit">
                                    Add listing
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        )
    }
}

export default ListingForm
